<?php

namespace App\Notifications;

use App\Models\Registration;
use App\Models\Event;
use App\Enums\AttendingOption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Rebooked extends Notification
{
    use Queueable;

    protected $registration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $event = Event::find($this->registration->event_id);

        $url = url('/'. $event->slug . '/ticket/' . $this->registration->uuid);
        $markdown = 'mail.registration.rebooked';

        return (new MailMessage)
            ->subject("Important Update: Your {$event->name} Slot")
            ->markdown($markdown, [
                'url' => $url,
                'name' => $this->registration->fullname,
                'event_name' => $event->name,
            ]);
            // ->attach($file, [
            //     'as' => 'event_details.pdf',
            //     'mime' => 'application/pdf',
            // ]);

    }
}
