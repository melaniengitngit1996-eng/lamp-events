<?php

namespace App\Http\Controllers;

use App\Enums\AttendingOption;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\AttendanceType;
use App\Enums\RegistrationType;
use App\Models\Booking;
use App\Models\Attendance;
use App\Models\Registration;
use App\Models\Slots;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index(Request $request) {
        return view('checkin.index', [
            'loc' => $request->lo_c == 2 ? 'Onsite' : 'Online'
        ]);
    }

    public function validation(Request $request) {
        $registration = Registration::with('bookings', 'bookings.slot')
            ->where('uuid', $request->referenceNumber)
            ->where('lastname', $request->lastName)
            ->where('local_church', $request->localChurch)
            ->where('registration_type', RegistrationType::Member)
            ->withSum('payments', 'amount')
            ->first();

        if (!$registration) {
            return response()->json(['error' => 'Not found. Please check the details and try again.'], 500);
        }

        if ($registration->is_booking_bypassed) {
            return response()->json(['error' => 'This delegate is a church worker and is already booked for the entire AWTA days.'], 500);
        }

        if ($registration->attending_option !== AttendingOption::Hybrid) {
            return response()->json(['error' => 'Delegate is not registered for hybrid.'], 500);
        }

        return [
            'delegate' => $registration,
            'slots' => Slots::where('registration_type', $registration->registration_type)->get(),
        ];
    }

    public function update($uuid, Request $request) {
        $registration = Registration::with('bookings', 'bookings.slot')->where('uuid', $uuid)->first();

        if (!$registration) {
            return response()->json(['error' => 'Not found. Please check the details and try again.'], 500);
        }

        $booking = $registration->bookings()->where('id', $request->booking)->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found.'], 500);
        }

        if ($booking->attendance_status === AttendanceType::Pending) {
            $registration->attendances()->create([
                'slot_id' => $booking->slot_id,
                'local_church' => $registration->local_church,
                'registration_type' => $registration->registration_type,
                'notes' => $request->loc == 'Online' ? AttendanceType::OnlineCheckIn : AttendanceType::OnsiteCheckIn
            ]);
        }

        return $registration->attendances()->get();
    }

    public function show(Request $request) {
        $attendance = Attendance::with('registration', 'slot')->whereIn('id', explode(',', $request->id))->get();

        $all = Attendance::where('registration_uuid', $attendance[0]->registration_uuid)->get()->pluck('id');

        return view('checkin.show', [
            'passes' => $attendance,
            'all' => implode(',', $all->toArray())
        ]);
    }
}
