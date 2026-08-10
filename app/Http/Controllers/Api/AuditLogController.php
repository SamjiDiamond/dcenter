<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class AuditLogController extends Controller
{
    /**
     * Per-request memo of resolved subject labels (type:id => name).
     *
     * @var array<string, string|null>
     */
    private static array $subjectNameCache = [];

    /**
     * Paginated, filterable audit log for the authenticated user's account.
     */
    public function index(Request $request)
    {
        $query = AuditTrail::with(['admin'])
            ->where('company_id', auth()->user()->company_id);

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('admin', function ($a) use ($search) {
                        $a->where('email', 'like', '%' . $search . '%')
                            ->orWhere('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Cap the page size so a client can't defeat pagination (same 100-row
        // ceiling the notifications feed uses).
        $perPage = min((int) $request->get('per_page', 20), 100);

        $logs = $query->latest()->paginate($perPage);

        // Entries written before the role column existed store no role; resolve
        // the actor's current role in one batched query (never N+1).
        $roleMap = $this->currentRolesFor($logs->getCollection()->pluck('admin_id'));

        $data = $logs->through(function ($log) use ($roleMap) {
            return [
                'id'               => $log->id,
                'actor'            => [
                    'name'  => $log->admin ? trim(($log->admin->first_name ?? '') . ' ' . ($log->admin->last_name ?? '')) : null,
                    'email' => $log->admin->email ?? null,
                    'role'  => $log->role ?: ($roleMap[$log->admin_id] ?? null),
                ],
                'action'           => $log->action,
                'description'      => $log->description,
                'subject'          => [
                    'type' => $log->subject_type,
                    'id'   => $log->subject_id,
                    'name' => $this->subjectName($log->subject_type, $log->subject_id),
                ],
                'severity'         => $log->severity,
                'ip_address'       => $log->ip_address,
                'browser'          => $this->parseBrowser($log->user_agent),
                'created_at'       => $log->created_at,
                'created_at_human' => $log->created_at?->diffForHumans(),
            ];
        });

        return response()->json([
            'status'  => 1,
            'message' => 'Audit logs fetched successfully',
            'data'    => $data,
        ]);
    }

    /**
     * Current role name for each admin id, resolved via the Bouncer
     * assigned_roles table in a single query. First role wins, matching
     * AuditService::roleNameFor.
     *
     * @param  \Illuminate\Support\Collection  $adminIds
     * @return array<int, string>
     */
    private function currentRolesFor($adminIds): array
    {
        $ids = $adminIds->filter()->unique()->values();

        if ($ids->isEmpty()) {
            return [];
        }

        $rows = DB::table('assigned_roles')
            ->join('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->where('assigned_roles.entity_type', User::class)
            ->whereIn('assigned_roles.entity_id', $ids)
            ->orderBy('roles.id')
            ->get(['assigned_roles.entity_id', 'roles.name']);

        $map = [];

        foreach ($rows as $row) {
            // Union keeps the first assignment per admin id.
            $map += [$row->entity_id => $row->name];
        }

        return $map;
    }

    /**
     * A human-readable label for the subject an entry affected ("what it
     * affected"), e.g. "Jane Doe" for a User subject. Returns null when the
     * subject can't be resolved. Results are memoized per request so repeated
     * subjects (e.g. "User #5 updated five times") are only fetched once.
     */
    private function subjectName($type, $id)
    {
        if (! $type || ! $id) {
            return null;
        }

        $key = $type . ':' . $id;

        if (array_key_exists($key, self::$subjectNameCache)) {
            return self::$subjectNameCache[$key];
        }

        $class = $this->resolveSubjectClass($type);

        if (! class_exists($class)) {
            return self::$subjectNameCache[$key] = null;
        }

        // The subject may reference a model whose table no longer exists (schema
        // drift); one bad row must never take down the whole audit page.
        try {
            $model = $class::find($id);
        } catch (\Throwable $e) {
            return self::$subjectNameCache[$key] = null;
        }

        if (! $model) {
            return self::$subjectNameCache[$key] = null;
        }

        $name = trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? ''));

        if ($name === '' && ! empty($model->name)) {
            $name = $model->name;
        }

        if ($name === '' && ! empty($model->email)) {
            $name = $model->email;
        }

        return self::$subjectNameCache[$key] = $name ?: null;
    }

    /**
     * AuditService::log stores short names ('User', 'Company', …) in some
     * call sites; map them to model classes so the subject can be resolved.
     */
    private function resolveSubjectClass($type)
    {
        $map = [
            'User'            => User::class,
            'users'           => User::class,
            'Company'         => \App\Models\Company::class,
            'company'         => \App\Models\Company::class,
            'Transaction'     => \App\Models\Transaction::class,
            'Deposit'         => \App\Models\Deposit::class,
            'VirtualAccount'  => \App\Models\VirtualAccount::class,
            'TwoFactorCode'   => \App\Models\TwoFactorCode::class,
        ];

        return $map[$type] ?? $type;
    }

    private function parseBrowser($userAgent)
    {
        if (! $userAgent) {
            return null;
        }

        try {
            $agent = new Agent();
            $agent->setUserAgent($userAgent);

            return trim($agent->browser() . ' ' . $agent->version($agent->browser()));
        } catch (\Throwable $e) {
            return null;
        }
    }
}
