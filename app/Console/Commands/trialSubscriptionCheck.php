<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class trialSubscriptionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trialSubscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check and update trial subscription';

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
       $trialSub = Subscription::where('trial_status', 'activated')->first();

       //dd($trialSub);

       if($trialSub){

            if(Carbon::now() >= $trialSub->trial_end_date){
                $trialSub->trial_status = "deactivated";
                $trialSub->save();
            }else{
                return;
            }
       }else{
           return;
       }

       
    }
}
