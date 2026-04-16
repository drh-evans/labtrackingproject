<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Module;

class ModuleEnrollmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Module $module)
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
        return (new MailMessage)
            ->subject('Added to Module')
            ->line('You have been added to the module **'.$this->module->module_code.', '.$this->module->module_name.' for '.$this->module->year_taught.'**.')
            ->line('This is accurate as of '.now()->format('d/m/Y @ H:i'))
            ->action('View the module here!', url('/modules/'.$this->module->id))
            ->line('If you believe this is an error please contact systemadmin@labtracker.com.');
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
