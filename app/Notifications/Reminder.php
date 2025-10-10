<?php

namespace App\Notifications;

use App\Models\Registration;
use App\Models\Event;
use App\Enums\AttendingOption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Reminder extends Notification
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

        if (in_array($this->registration->attending_option, [AttendingOption::Hybrid, AttendingOption::Physical])) {
            $url = url('/'. $event->slug . '/ticket/' . $this->registration->uuid);
            $markdown = 'mail.registration.reminder';
            $file = storage_path(). "/images/event_details.pdf";
        } else {
            $url = $event->fb_group_url;
            $markdown = 'mail.registration.online.reminder';
            $file = storage_path(). "/images/programme.pdf";
        }

        if (env('TEST_MAIL') == true) {
            $url = $event->fb_group_url;
            $markdown = 'mail.registration.online.reminder';
            $file = storage_path(). "/images/programme.pdf";
        }

        return (new MailMessage)
            ->subject("Reminder: Upcoming {$event->name} TOMORROW!")
            ->markdown($markdown, [
                'url' => $url,
                'name' => $this->registration->fullname,
                'event_name' => $event->name,
                'theme' => $event->description,
                'fb_group_url' => $event->fb_group_url,
                'venue' => $event->venue_complete_address,
                'event_timing' => $event->event_timing,
                'event_date' => $event->event_date,
                'enable_zoom_registration' => $event->enable_zoom_registration,
                'zoom' => [
                    'link' => $event->zoom_url,
                    'id' => $event->zoom_id,
                    'passcode' => $event->zoom_password,
                ]
            ]);
            // ->attach($file, [
            //     'as' => 'event_details.pdf',
            //     'mime' => 'application/pdf',
            // ]);

    }
}
