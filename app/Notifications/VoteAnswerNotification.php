<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VoteAnswerNotification extends Notification
{
    use Queueable;

    private $notification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = (object) $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ($notifiable->email_notifications == 1) ? ['mail', 'database'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(__('Hello!'))
            ->line(__('Your answer was voted').': ')
            ->line($this->notification->answer)
            ->action(__('View Answer'), url($this->notification->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->notification->id,
            'message' => __('Your answer was voted').': '.$this->notification->answer,
            'url' => $this->notification->url,
        ];
    }
}
