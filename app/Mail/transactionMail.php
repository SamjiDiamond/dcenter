<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Checkout;

class transactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $checkout;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Checkout $checkout=null)
    {
        $this->user = $user;
        $this->checkout = $checkout;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.transaction');
    }
}
