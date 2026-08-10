<?php

namespace App\Jobs;

use App\Mail\FundwalletMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class FundwalletJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\User|null  $user  the customer whose wallet was funded
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Email the customer about their wallet top-up.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->user) {
            return;
        }

        Mail::to($this->user)->send(new FundwalletMail($this->user));
    }
}
