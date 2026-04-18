<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private string $resetToken)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        // password reset link
        $resetUrl = route('password.reset',['token'=>$this->resetToken, 'email'=>$notifiable->email]);
        return (new MailMessage)
            ->subject('A LabTracker account has been made for you!')
            ->line('A LabTracker account has been made for you using this email address.')
            ->line('Please set your password below.')
            ->action('Create Password', $resetUrl)
            ->line('This link will expire in 60 minutes.')
            ->line('LabTracker is your home for labs at Swansea University.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
