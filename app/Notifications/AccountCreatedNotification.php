<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    public $firstName;

    /**
     * Create a new notification instance.
     *
     * @param  string|null  $firstName
     */
    public function __construct($firstName = null)
    {
        $this->firstName = $firstName;
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
            'module' => 'account',
            'text'   => 'Welcome' . ($this->firstName ? ', ' . $this->firstName : '') . '! Your account has been created successfully.',
        ];
    }
}
