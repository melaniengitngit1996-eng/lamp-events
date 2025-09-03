<?php

namespace App\Http\Controllers;

use App\Enums\AttendingOption;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationType;
use App\Models\Booking;
use App\Models\LookUp;
use App\Models\Registration;
use App\Notifications\Registered;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Notification;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateGuestId()
    {
        $lastGuestId = Registration::select('uuid')->where('registration_type', RegistrationType::Guest)->orderBy('id', 'desc')->first();

        if ($lastGuestId) {
            $number = last(explode("GUEST", $lastGuestId['uuid'])); //explode the string to get the number part, last is a laravel helper
        } else {
            $number = 0;
        }
        $new = str_pad(intval($number) + 1, 4, 0, STR_PAD_LEFT); //increment the number by 1 and pad with 0 in left.

        $prefix = "GUEST";

        return $prefix . $new;
    }

    function book($event, $registration, $bookings)
    {
        $registration->update([
            'booked_date' => now()
        ]);

        foreach ($bookings as $key => $booking) {
            $book = [
                'event_id' => $registration->event_id,
                'registration_id' => $registration->id,
                'slot_id' => $booking,
                'local_church' => $registration['local_church'],
                'status' => $registration->payment_status === PaymentStatus::Paid || $registration->payment_status === PaymentStatus::Free ? BookingStatus::Confirmed : BookingStatus::Pending,
                'venue' => $event->main_venue
            ];

            if ($event->has_multiple_venues) {
                $book['slot_id'] = $key;
                $book['venue'] = $booking;
            }

            Booking::create($book);
        }
    }

    function updatePaymentStatus($registration_id, $auto_enable_booking)
    {
        $registration = Registration::with('bookings', 'bookings.slot')->withSum('payments', 'amount')->where('id', $registration_id)->first();

        $balance = floatval($registration->rate);

        $canBookRate = floatval($registration->can_book_rate);

        $totalAmountPaid = floatval(array_sum(array_column($registration->payments->toArray(), 'amount')));

        $balance -= $totalAmountPaid;

        $parameters = array();

        $bookingStatusUpdated = false;

        if ($balance <= 0.0 && count($registration->payments) > 0) {
            $parameters['payment_status'] = PaymentStatus::Paid;
            if ($registration->booking_status === BookingStatus::Pending) {
                $parameters['booking_status'] = BookingStatus::Confirmed;
                $bookingStatusUpdated = true;
            }
        }

        if ($balance > 0.0 && count($registration->payments) > 0) {
            $parameters['payment_status'] = PaymentStatus::Partial;
            if ($registration->booking_status === BookingStatus::Pending && floatval($registration->can_book_rate) <= floatval($registration->payments_sum_amount)) {
                $parameters['booking_status'] = BookingStatus::Confirmed;
                $bookingStatusUpdated = true;
            }
        }

        if ($balance == 0.0 && count($registration->payments) == 0) {
            $parameters['payment_status'] = PaymentStatus::Free;
            $parameters['booking_status'] = BookingStatus::Confirmed;

            if ($registration->booking_status === BookingStatus::Pending) {
                $bookingStatusUpdated = true;
            }
        }

        if ($balance > 0.0 && count($registration->payments) == 0) {
            $parameters['payment_status'] = PaymentStatus::Unsettled;
            $parameters['booking_status'] = BookingStatus::Pending;
        }

        if ($auto_enable_booking && in_array($registration->attending_option, [AttendingOption::Hybrid, AttendingOption::Physical])) {
            if ($totalAmountPaid >= $canBookRate) {
                if ($registration->booking_status === BookingStatus::Pending) {
                    Booking::where('registration_id', $registration->id)->update([
                        'status' => BookingStatus::Confirmed
                    ]);

                    $registration->update([
                        'booking_status' => BookingStatus::Confirmed
                    ]);

                    $bookingStatusUpdated = true;
                }
            }
        }

        $registration->update($parameters);

        $onlineMemberRegistrantsPaid = $registration->attending_option === AttendingOption::Online && $registration->registration_type === RegistrationType::Member && $registration->payment_status === PaymentStatus::Paid;

        if (true === $bookingStatusUpdated || true === $onlineMemberRegistrantsPaid) {
            $this->notify($registration->id);
        }

        return $registration;
    }

    function notify($id)
    {
        $registration = Registration::with('bookings', 'bookings.slot')->withSum('payments', 'amount')->find($id);

        if ($registration->email) {
            Notification::route('mail', [
                $registration->email => $registration->fullname,
            ])->notify(new Registered($registration));
        }
    }

    function remind($id) {
        $registration = Registration::with('bookings', 'bookings.slot')->withSum('payments', 'amount')->find($id);

        if ($registration->email) {
            Notification::route('mail', [
                $registration->email => $registration->fullname,
            ])->notify(new Reminder($registration));
        }
    }

    function checkIfAlreadyRegistered($event_id, $request)
    {
        $registration = Registration::where('event_id', $event_id)
            ->where('firstname', $request->firstName)
            ->where('lastname', $request->lastName)
            ->where('local_church', $request->localChurch)
            ->first();

        if ($registration) {
            return ['error' => 'This delegate from ' . $request->localChurch . ' is already registered.'];
        }

        $lookup = LookUp::where('firstname', $request->firstName)
            ->where('lastname', $request->lastName)
            ->where('local_church', $request->localChurch)
            ->first();

        if ($lookup) {
            return ['error' => 'This delegate from ' . $request->localChurch . ' has already been issued with a LAMP ID number.'];
        }

        return [];
    }
}
