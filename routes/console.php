<?php

use App\Enums\BookingStatus;
use App\Models\Registration;
use App\Enums\AttendingOption;
use App\Notifications\Registered;
use App\Notifications\Reminder;
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
        if ($registration->email != '') {
            Notification::route('mail', [
                $registration->email => $registration->fullname,
            ])->notify(new Reminder($registration));

            $this->comment($registration->id . ' - sent reminder to ' . $registration->fullname . ' - ' . $registration->email);
        } else {
            $this->comment($registration->id . ' - reminder not sent for ' . $registration->fullname . ' - [no email address provided]');
        }
    }
    $this->comment('---------------------------------- end ---------------------------------');
});

Artisan::command('send-out-event-reminder-online', function () {
    $this->comment('---------------------------------- ' . \Carbon\Carbon::today() . ' ---------------------------------');
    if (env('TEST_MAIL') == true) {
        $registration = Registration::where('uuid', 'LAMP00002')->first();
        Notification::route('mail', [
            $registration->email => $registration->fullname,
        ])->notify(new Reminder($registration));
    } else {
        $registrations = Registration::where('attending_option', AttendingOption::Online)->where('id', '>', 2755)->get();
        
        foreach ($registrations as $registration) {
            if ($registration->email != '') {
                Notification::route('mail', [
                    $registration->email => $registration->fullname,
                ])->notify(new Reminder($registration));

                $this->comment($registration->id . ' - sent reminder to ' . $registration->fullname . ' - ' . $registration->email);
            } else {
                $this->comment($registration->id . ' - reminder not sent for ' . $registration->fullname . ' - [no email address provided]');
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
