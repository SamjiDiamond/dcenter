<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeamMemberNotification extends Notification
{
    use Queueable;

    public $role;
    public $companyName;

    /**
     * Create a new notification instance.
     *
     * @param  string  $role  admin|finance|viewer
     * @param  string|null  $companyName
     */
    public function __construct($role, $companyName = null)
    {
        $this->role = $role;
        $this->companyName = $companyName;
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
        $where = $this->companyName ? ' for ' . $this->companyName : '';

        return [
            'module' => 'team',
            'role'   => $this->role,
            'text'   => 'You were added to the team' . $where . ' as ' . ucfirst($this->role) . '.',
        ];
    }
}
