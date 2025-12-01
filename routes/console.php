<?php

use App\Enums\BookingStatus;
use App\Models\Registration;
use App\Enums\AttendingOption;
use App\Enums\PaymentStatus;
use App\Notifications\Registered;
use App\Notifications\Reminder;
use App\Notifications\Rebooked;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

/* run `php artisan send-out-event-reminder 7` */
Artisan::command('send-out-event-reminder {event_id?}', function () {
    $this->comment('---------------------------------- ' . \Carbon\Carbon::today() . ' ---------------------------------');
    $eventId = $this->argument('event_id');

    $registrations = Registration::where('event_id', $eventId)->whereIn('attending_option', [
        AttendingOption::Hybrid,
        AttendingOption::Physical
    ])->get();
    
    foreach ($registrations as $registration) {
        $email = trim((string) $registration->email);
    
        // Skip if empty or doesn't contain @
        if ($email === '' || strpos($email, '@') === false) {
            $this->comment("{$registration->id} - reminder not sent for {$registration->fullname} - [invalid email] {$email}");
            continue;
        }
    
        // Validate format properly
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->comment("{$registration->id} - reminder not sent for {$registration->fullname} - [invalid format] {$email}");
            continue;
        }
    
        try {
            Notification::route('mail', [$email => $registration->fullname])
                ->notify(new Reminder($registration));
    
            $this->comment("{$registration->id} - sent reminder to {$registration->fullname} - {$email}");
        } catch (\Throwable $e) {
            $this->comment("{$registration->id} - failed to send reminder to {$registration->fullname} - {$email}");
        }
    }
    $this->comment('---------------------------------- end ---------------------------------');
});

/* run `php artisan send-out-event-reminder-online 7` */
Artisan::command('send-out-event-reminder-online {event_id?} {registration_id?}', function () {
    $this->comment('---------------------------------- ' . \Carbon\Carbon::today() . ' ---------------------------------');
    $eventId = $this->argument('event_id');
    $registrationId = $this->argument('registration_id');
    if ($registrationId) {
        $registration = Registration::where('id', $registrationId)->first();
       

        try {
            Notification::route('mail', [
                $registration->email => $registration->fullname,
            ])->notify(new Reminder($registration));
    
            $this->comment("{$registration->id} - sent reminder to {$registration->fullname} - {$registration->email}");
        } catch (\Throwable $e) {
            $this->comment("{$registration->id} - failed to send reminder to {$registration->fullname} - {$email}");
        }
    } else {
        $registrations = Registration::where('event_id', $eventId)->where('attending_option', AttendingOption::Online)->get();
        
        foreach ($registrations as $registration) {
            $email = trim((string) $registration->email);
        
            // Skip if empty or doesn't contain @
            if ($email === '' || strpos($email, '@') === false) {
                $this->comment("{$registration->id} - reminder not sent for {$registration->fullname} - [invalid email] {$email}");
                continue;
            }
        
            // Validate format properly
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->comment("{$registration->id} - reminder not sent for {$registration->fullname} - [invalid format] {$email}");
                continue;
            }
        
            try {
                Notification::route('mail', [$email => $registration->fullname])
                    ->notify(new Reminder($registration));
        
                $this->comment("{$registration->id} - sent reminder to {$registration->fullname} - {$email}");
            } catch (\Throwable $e) {
                $this->comment("{$registration->id} - failed to send reminder to {$registration->fullname} - {$email}");
            }
        }        
        
    }

    $this->comment('---------------------------------- end ---------------------------------');
});

/* run `php artisan cancel-bookings 1` */
Artisan::command('cancel-bookings {event_id?}', function () {
    $eventId = $this->argument('event_id');
    $date = \Carbon\Carbon::today()->subDays(7);
    $this->comment('---------------------------------- ' . $date . ' ---------------------------------');
    \Log::info('---------------------------------- ' . $date . ' ---------------------------------');

    // get all registrations that have not been paid for more than seven days since they were booked
    $registrations = Registration::withSum('payments', 'amount')->where('event_id', $eventId)->where('booked_date', '<=', $date)->where('booking_status', BookingStatus::Pending)->get();

    foreach ($registrations as $registration) {
        if (floatval($registration->can_book_rate) > floatval($registration->payments_sum_amount)) {
            $registration->bookings()->update([
                'status' => BookingStatus::Cancelled
            ]);

            $registration->update([
                'booking_status' => BookingStatus::Cancelled
            ]);

            $registration = Registration::with('bookings', 'bookings.slot')->withSum('payments', 'amount')->find($registration->id);

            if ($registration->email) {
                Notification::route('mail', [
                    $registration->email => $registration->fullname,
                ])->notify(new Registered($registration));
            }

            $registration->updateBookingActivities($registration, $registration->booking_activities, array('<b>System:</b> Booking cancelled due to unsettled payment.'));

            \Log::info('[' . $registration->uuid . '] ' . $registration->fullname . '\'s booking is now cancelled. Date Booked: ' . $registration->booked_date);
            $this->comment('[' . $registration->uuid . '] ' . $registration->fullname . '\'s booking is now cancelled. Date Booked: ' . $registration->booked_date);
        }
    }

    if (count($registrations) === 0) {
        $this->comment('No expired booking found.');
        \Log::info('No expired booking found.');
    }
})->purpose('Booking cancellation for unsettled registrations');

Artisan::command('rebook-to-sattelite-partial-bookings {event_id?}', function () {
    $eventId = $this->argument('event_id');

    // $registrations = Registration::with('bookings')->where('event_id', $eventId)->where('payment_status', PaymentStatus::Partial)->where('attending_option', AttendingOption::Physical)->where('rate', 950)->get();

    $registrations = Registration::with('bookings')->where('event_id', $eventId)->where('id', 13)->get();

    foreach ($registrations as $registration) {
        $registration->bookings()->update([
            'venue' => 'Local Church'
        ]);

        $registration->update([
            'rate' => 100,
            'can_book_rate' => 100,
            'custom_fields->venue' => 'Local Church'
        ]);

        $registration->updateActivities($registration, $registration->activities, array(
            'Tagged to satellite due to unsettled balance'
        ));

        $email = $registration->email;

        try {
            Notification::route('mail', [$registration->email => $registration->fullname])
                ->notify(new Rebooked($registration));
    
            $this->comment("{$registration->id} - sent reminder to {$registration->fullname} - {$email}");
        } catch (\Throwable $e) {
            $this->comment("{$registration->id} - failed to send reminder to {$registration->fullname} - {$email}");
        }

        \Log::info('[' . $registration->uuid . '] ' . $registration->fullname . '\'s booking is not yet settled. Date Booked: ' . $registration->booked_date);
        $this->comment('[' . $registration->uuid . '] ' . $registration->fullname . '\'s booking is not yet settled. Date Booked: ' . $registration->booked_date);
    }

    if (count($registrations) === 0) {
        $this->comment('No expired booking found.');
        \Log::info('No expired booking found.');
    }
});