<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TransactionNotification extends Notification
{
    use Queueable;

    public $amount;
    public $type;
    public $description;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $amount
     * @param  string  $type  e.g. "charge", "airtime", "recharge_card"
     * @param  string|null  $description
     */
    public function __construct($amount, $type, $description = null)
    {
        $this->amount = (float) $amount;
        $this->type = $type;
        $this->description = $description;
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
            'module'      => 'transaction',
            'text'        => 'A ' . $this->type . ' transaction of ₦' . number_format($this->amount, 2) . ' was posted to your account.',
            'amount'      => $this->amount,
            'type'        => $this->type,
            'description' => $this->description,
        ];
    }
}
