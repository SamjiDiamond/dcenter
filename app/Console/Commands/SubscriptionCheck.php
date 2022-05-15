<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class SubscriptionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update subscription status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subStatus = Subscription::where('plan_status', 'active')->first();

        if($subStatus){
            
            if(Carbon::now() >= $subStatus->plan_end_date){
                $subStatus->plan_status = "deactivated";
                $subStatus->save();
            }else{
                return;
            }
        }else{
            return;
        }

    }
}
