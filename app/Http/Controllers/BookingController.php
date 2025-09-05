<?php

namespace App\Http\Controllers;

use App\Enums\AttendingOption;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\Registration;
use App\Models\Slots;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'index', 'show', 'validation', 'update']]);
    }

    public function create(Event $event)
    {
        $event = Event::with(['custom_fields', 'venues'])->find($event->id);
        return view('booking.create', [
            'event' => $event
        ]);
    }

    public function edit(Event $event, Registration $registration)
    {
        $event = Event::with(['custom_fields', 'venues'])->find($event->id);

        return view('booking.edit', [
            'booked_dates' => $registration->bookings()->with(['slot'])->where('status', '!=', BookingStatus::Cancelled)->get(),
            'slots' => Slots::where('event_id', $registration->event_id)->where('registration_type', $registration->registration_type)->get(),
            'registration' => $registration,
            'event' => $event
        ]);
    }

    /**
     * show booking ticket.
     *
     * @param  String $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($registration_id)
    {
        $registration = Registration::with('bookings', 'bookings.slot')->where('id', $registration_id)->first();

        $registration->booked_dates = array_map(function ($dates) {
            return $dates['slot']['event_date'];
        }, $registration->bookings->toArray());

        return view('booking.show', [
            'registration' => $registration
        ]);
    }

    public function update(Event $event, $registration_id, Request $request)
    {
        $registration = Registration::withSum('payments', 'amount')->where('id', $registration_id)->first();

        if ($registration->is_booking_bypassed) {
            return response()->json(['error' => 'This delegate is a church worker and is already booked for the entire AWTA days.'], 500);
        }

        $new_booked_dates = $request->all()['dates'];

        $old_booked_dates = array_column($registration->bookings()->get()->toArray(), 'slot_id');

        if ($event->has_multiple_venues) {
            $old_booked_dates = array_column(
                $registration->bookings()->get()->toArray(),
                'venue', 
                'slot_id'
            );
        }

        $limit = $registration->rebooking_limit;

        // if admin did the rebooking, rebooking limit must not be deducted.
        $hasPermission = $request->is_admin === 1;

        // considered paid if partially paid
        $paid = $registration->payment_status === PaymentStatus::Paid || floatval($registration->can_book_rate) <= floatval($registration->payments_sum_amount);

        $hasChanges = false;

        // has changes if old vs new booked dates are different
        if ($new_booked_dates != $old_booked_dates || $registration->booking_status === BookingStatus::Cancelled) {
            $hasChanges = true;
        }

        // has changes if paid
        if ($new_booked_dates == $old_booked_dates && $paid && ($registration->booking_status === BookingStatus::Pending || $registration->booking_status === BookingStatus::Cancelled)) {
            $hasChanges = true;
        }

        // if no changes or admin, booking limit wont be deducted
        $limit = !$hasChanges || $hasPermission ? $limit : $limit - 1;

        // delete current bookings
        $registration->bookings()->delete();

        // set default booking status based on its initial value
        $booking_status = $registration->booking_status;

        // set booking status as confirmed if has changes and is paid
        if ($hasChanges && $paid) {
            $booking_status = BookingStatus::Confirmed;
        }

        // set booking status as pending if has changes but not yet paid
        if ($hasChanges && !$paid) {
            $booking_status = BookingStatus::Pending;
        }

        foreach ($request->dates as $key => $date) {
            $slot_id = $event->has_multiple_venues ? $key : $date;
            $venue = $event->has_multiple_venues ? $date : $event->main_venue;
            $taken = Booking::where('slot_id', $slot_id)->count();
            $slot = Slots::where('id', $slot_id)->first();
            $remaining = $slot->available;

            if ($remaining > 0) {
                // update booking status, deduct to rebooking limit
                $registration->update([
                    'booking_status' => $booking_status,
                    'rebooking_limit' => $limit
                ]);

                $new_booking = [
                    'slot_id' => $slot_id,
                    'event_id' => $event->id,
                    'local_church' => $registration->local_church,
                    'status' => $booking_status,
                    'venue' => $venue
                ];

                // store bookings
                $registration->bookings()->create($new_booking);
            } else {
                return response()->json(['error' => 'Sorry, no remaining seats left. Please refresh the page and try again.'], 500);
            }
        }

        if ($hasChanges && count($new_booked_dates) > 0) {
            // add activity to registration
            $dates = array_map(function ($date) use ($event) {
                if ($event->has_multiple_venues) {
                    return $date['slot']['event_date'] . ' (' . $date['venue'] . ')';
                } else {
                    return $date['slot']['event_date'];
                }
            }, $registration->bookings()->with('slot')->get()->toArray());

            $registration->update([
                'booked_date' => NOW()
            ]);

            if ($hasPermission) {
                $registration->updateBookingActivities($registration, $registration->booking_activities, array('<b>' . auth()->user()->name . '</b>: Updated to ' . implode(', ', $dates)));
            } else {
                $registration->updateBookingActivities($registration, $registration->booking_activities, array('<b>' . $registration->fullname . '</b>: Rebooked to ' . implode(', ', $dates)));
            }
        }

        if (count($new_booked_dates) === 0 && count($old_booked_dates) > 0) {
            $registration->updateBookingActivities($registration, $registration->booking_activities, array('<b>' . auth()->user()->name . '</b>: Removed all the booked dates'));
        }

        if ($hasChanges) {
            $this->notify($registration->id);
        }

        return $registration->bookings()->with(['slot'])->get();
    }

    public function validation(Event $event, Request $request)
    {
        $registration = Registration::with('bookings', 'bookings.slot')
            ->where('event_id', $event->id)
            ->where('uuid', $request->referenceNumber)
            ->where('lastname', $request->lastName)
            ->where('local_church', $request->localChurch)
            ->withSum('payments', 'amount')
            ->first();

        if (!$registration) {
            return response()->json(['error' => 'Not found. Please check the details and try again.'], 500);
        }

        if ($registration->is_booking_bypassed) {
            return response()->json(['error' => 'This delegate is a church worker and is already booked for the entire AWTA days.'], 500);
        }

        if ($registration->rebooking_limit <= 0) {
            return response()->json(['error' => 'Already reached rebooking limit.'], 500);
        }

        if (!in_array($registration->attending_option, [AttendingOption::Hybrid, AttendingOption::Physical])) {
            return response()->json(['error' => 'Delegate is not registered for physical/hybrid.'], 500);
        }

        $registration->booked_dates = array_map(function ($dates) use ($event) {
            if ($event->has_multiple_venues) {
                return [
                    'event_date' => $dates['slot']['event_date'],
                    'venue' => $dates['venue']
                ];
            } else {
                return $dates['slot']['event_date'];
            }
        }, $registration->bookings->toArray());

        return [
            'delegate' => $registration,
            'slots' => Slots::where('event_id', $event->id)->where('registration_type', $registration->registration_type)->get(),
        ];
    }
}
