<?php

namespace App\Console\Commands;

use App\Models\AuditTrail;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\TwoFactorCode;
use App\Models\User;
use App\Models\Verification;
use App\Models\VirtualAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProcessAccountDeletions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:process-deletions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete accounts whose 7-day deletion window has elapsed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereNotNull('deletion_scheduled_for')
            ->where('deletion_scheduled_for', '<=', now())
            ->get();

        if ($users->isEmpty()) {
            $this->info('No accounts due for deletion.');

            return 0;
        }

        foreach ($users as $user) {
            $email = $user->email;

            try {
                DB::transaction(function () use ($user) {
                    VirtualAccount::where('user_id', $user->id)->delete();
                    Transaction::where('user_id', $user->id)->delete();
                    Deposit::where('user_id', $user->id)->delete();
                    Verification::where('user_id', $user->id)->delete();
                    TwoFactorCode::where('user_id', $user->id)->delete();

                    DB::table('notifications')
                        ->where('notifiable_id', $user->id)
                        ->where('notifiable_type', User::class)
                        ->delete();

                    DB::table('assigned_roles')->where('entity_id', $user->id)->delete();
                    AuditTrail::where('admin_id', $user->id)->delete();

                    DB::table('personal_access_tokens')
                        ->where('tokenable_id', $user->id)
                        ->where('tokenable_type', User::class)
                        ->delete();

                    // These tables were created out-of-band; guard against missing columns.
                    foreach (['payouts' => 'user_id', 'smslog' => 'user_id', 'sms_payments' => 'user_id'] as $table => $column) {
                        if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                            DB::table($table)->where($column, $user->id)->delete();
                        }
                    }

                    $user->delete();
                });

                $this->info("Deleted account: {$email}");
            } catch (\Throwable $e) {
                $this->error("Failed to delete {$email}: {$e->getMessage()}");
            }
        }

        return 0;
    }
}
