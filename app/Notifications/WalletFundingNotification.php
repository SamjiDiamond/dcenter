<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WalletFundingNotification extends Notification
{
    use Queueable;

    public $amount;
    public $reference;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $amount
     * @param  string|null  $reference
     */
    public function __construct($amount, $reference = null)
    {
        $this->amount = (float) $amount;
        $this->reference = $reference;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification (stored in the
     * notifications table via the database channel).
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'module'    => 'wallet_funding',
            'text'      => 'Your wallet was funded with ₦' . number_format($this->amount, 2) . '.',
            'amount'    => $this->amount,
            'reference' => $this->reference,
        ];
    }
}
