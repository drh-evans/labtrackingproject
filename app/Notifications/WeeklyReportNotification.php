<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Module;

class WeeklyReportNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Module $module, public array $missingStudents)
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
        $message = (new MailMessage)
            ->subject('Module progress report for '.$this->module->module_code)
            ->line($notifiable->name.' this report contains students who have missed 2 consecutive labs in your module **'. 
                    $this->module->module_code . ", ".$this->module->module_name."**.");
            
            foreach ($this->missingStudents as $student) {
                $message->line($student['student']->name. ' (**'.$student['student']->email.')**');
            }

        $message->action('Dashboard', url('/modules/'.$this->module))
            ->line('Please ensure you contact these students to see if they require additional support.');
        return $message;
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
