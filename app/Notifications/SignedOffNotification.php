<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\LabTask;
use App\Models\Module;

class SignedOffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public LabTask $task, public Module $module)
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
            ->subject('Progress Made!')
            ->line('You have been signed-off for **'.$this->task->task_name.'** in '.$this->module->module_code.', '.$this->module->module_name.'.')
            ->line('This is accurate as of '.now()->format('d/m/Y @ H:i'))
            ->line('This task is worth: **'.$this->task->total_points. '** marks')
            ->action('See your progress here!', url('/modules/'.$this->module->id))
            ->line('If you believe this information is wrong please contact a TA or your lecturer.');
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
