<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:prune {--keep=200 : Number of the most recent notifications to keep per user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old notifications so each user only keeps their most recent ones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $keep = max(1, (int) $this->option('keep'));

        // Every distinct recipient present in the notifications table.
        $recipients = DB::table('notifications')
            ->select('notifiable_id', 'notifiable_type')
            ->groupBy('notifiable_id', 'notifiable_type')
            ->get();

        if ($recipients->isEmpty()) {
            $this->info('No notifications to prune.');

            return 0;
        }

        $totalDeleted = 0;

        foreach ($recipients as $recipient) {
            $query = DB::table('notifications')
                ->where('notifiable_id', $recipient->notifiable_id)
                ->where('notifiable_type', $recipient->notifiable_type)
                ->orderByDesc('created_at')
                ->orderByDesc('id'); // deterministic tiebreaker at the keep boundary

            $total = $query->count();

            if ($total <= $keep) {
                continue;
            }

            // IDs older than the newest $keep, deleted in small chunks so the
            // query never has to hold thousands of ids at once.
            $deleted = 0;
            $ids = $query->offset($keep)->limit(1000)->pluck('id');

            while ($ids->isNotEmpty()) {
                $deleted += DB::table('notifications')
                    ->whereIn('id', $ids)
                    ->delete();

                $ids = $query->offset($keep)->limit(1000)->pluck('id');
            }

            $totalDeleted += $deleted;

            $this->line(sprintf(
                '[%s #%s] kept %d of %d, deleted %d',
                $recipient->notifiable_type,
                $recipient->notifiable_id,
                $keep,
                $total,
                $deleted
            ));
        }

        $this->info("Notification cleanup complete. Deleted {$totalDeleted} old notification(s).");

        return 0;
    }
}
