<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;

class AuditController extends Controller
{
    public function index()
    {
        $query = AuditTrail::with(['admin', 'company'])->orderByDesc('created_at');

        $companyId = (int) auth()->user()->company_id;

        // Only the platform account (company 1) may view another company's audit
        // trail via the companyId filter; everyone else stays scoped to their own.
        if ($companyId === 1 && request()->filled('companyId')) {
            $query->where('company_id', request('companyId'));
        } else {
            $query->where('company_id', $companyId);
        }

        if (request()->filled('adminId')) {
            $query->where('admin_id', request('adminId'));
        }

        if (request()->filled('severity')) {
            $query->where('severity', request('severity'));
        }

        if (request()->filled('action')) {
            $query->where('action', 'like', '%' . request('action') . '%');
        }

        if (request()->filled('date')) {
            $query->whereDate('created_at', request('date'));
        }

        $auditTrails = $query->paginate(25)->withQueryString();

        $auditTrails->getCollection()->transform(function ($trail) {
            $trail->browser_label = $this->browserLabel($trail->user_agent);

            return $trail;
        });

        $sn = ($auditTrails->currentPage() - 1) * $auditTrails->perPage() + 1;

        return view('report_audittrail', compact('auditTrails', 'sn'));
    }

    /**
     * Turn a raw User-Agent string into a short "Browser on OS" label.
     *
     * @param  string|null  $userAgent
     * @return string
     */
    private function browserLabel($userAgent)
    {
        if (! $userAgent) {
            return '—';
        }

        $browser = 'Unknown browser';
        $browsers = [
            // Edge must be checked before Chrome — its UA contains both "Edg/" and "Chrome/".
            'Edge'     => '/Edg\/([\d.]+)/',
            'Chrome'   => '/Chrome\/([\d.]+)/',
            'Firefox'  => '/Firefox\/([\d.]+)/',
            'Opera'    => '/OPR\/([\d.]+)/',
            'Safari'   => '/Version\/([\d.]+).*Safari/',
            'MSIE'     => '/MSIE ([\d.]+)/',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $m)) {
                $browser = $name . ' ' . $m[1];
                break;
            }
        }

        $os = 'Unknown OS';
        $oses = [
            'Windows' => '/Windows NT ([\d.]+)/',
            'macOS'   => '/Mac OS X ([\d_]+)/',
            'Android' => '/Android ([\d.]+)/',
            'iOS'     => '/iPhone OS ([\d_]+)/',
            'Linux'   => '/Linux/',
        ];

        foreach ($oses as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $m)) {
                $os = $name . (isset($m[1]) ? ' ' . str_replace('_', '.', $m[1]) : '');
                break;
            }
        }

        return $browser . ' · ' . $os;
    }
}
