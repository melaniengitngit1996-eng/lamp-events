<?php

namespace App\Http\Controllers;

use App\Enums\AttendingOption;
use App\Enums\AttendanceType;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Registration;
use App\Models\Event;
use App\Models\Slots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportAttendance;
use PhpParser\Node\Expr\Cast\Object_;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show', 'store']]);
    }

    public function all(Event $event, Request $request) {
        $search = json_decode($request->search);

        $attendances = Attendance::with('registration', 'slot')->where('event_id', $event->id);

        if ($search->registration_type) {
            $attendances = $attendances->whereRelation('registration', 'registration_type', '=', $search->registration_type);
        }

        if ($search->local_church) {
            $attendances = $attendances->whereRelation('registration', 'local_church', '=', $search->local_church);
        }

        if ($search->keyword) {
            $attendances = $attendances->whereRelation('registration', 'fullname', 'LIKE', "%$search->keyword%")
                ->orWhereRelation('registration', 'uuid', 'LIKE', "%$search->keyword%");
        }

        return $attendances->paginate(10);
    }

    public function index(Event $event, Request $request)
    {
        $local_churches = explode(',', env('LOCAL_CHURCHES'));

        $slots = Slots::where('registration_type', 'Member')->where('id', $event->active_member_slot_id)->get();
        $attendance_count = [];

        foreach ($slots as $slot) {
            $slot = (object) $slot;
            $count = [];

            $member = Slots::where('event_date', $slot['event_date'])->where('registration_type', 'Member')->first();
            $guest = Slots::where('event_date', $slot['event_date'])->where('registration_type', 'Guest')->first();

            foreach ($local_churches as $local_church) {
                $array = [];

                $array['local_church'] = $local_church;
                $array['count'] = array(
                    'member' => array(
                        'total' => DB::table('bookings')
                            ->where('local_church', $local_church)
                            ->where('slot_id', $member->id)
                            ->where('status', BookingStatus::Confirmed)
                            ->count(),
                        'attended' => DB::table('attendances')
                            ->where('local_church', $local_church)
                            ->where('slot_id', $member->id)
                            ->count(),
                    ),
                    'guest' => array(
                        'total' => DB::table('bookings')
                            ->where('local_church', $local_church)
                            ->where('slot_id', $guest->id)
                            ->where('status', BookingStatus::Confirmed)
                            ->count(),
                        'attended' => DB::table('attendances')
                            ->where('local_church', $local_church)
                            ->where('slot_id', $guest->id)
                            ->count(),
                    )
                );

                array_push($count, $array);
            }

            $slot['count'] = $count;

            $slot['event_date'] = date_format($slot['event_date'], 'F d');

            array_push($attendance_count, $slot);
        }

        return view('attendance.index', [
            'count' => json_encode($attendance_count),
            'guest_current_date' => Slots::where('id', $event->active_guest_slot_id)->first()->event_date,
            'member_current_date' => Slots::where('id', $event->active_member_slot_id)->first()->event_date,
            'event' => $event
        ]);
    }

    public function show(Event $event, $uuid, Request $request)
    {
        if (!$uuid) {
            return response()->json(['error' => 'Please enter LAMP ID/Guest number.'], 500);
        }

        $registration = Registration::where('uuid', $uuid)->where('event_id', $event->id)->first();

        if (!$registration) {
            return response()->json(['error' => 'Not found. Please check the number and try again.'], 500);
        }

        $slot_id = $registration->registration_type === 'Member' ? $event->active_member_slot_id : $event->active_guest_slot_id;

        $isBooked = $registration->bookings()->where('slot_id', $slot_id)->first();

        if (!$isBooked) {
            return response()->json(['error' => 'This delegate is not booked for today.'], 500);
        }

        if (in_array($registration->attending_option, [AttendingOption::Hybrid, AttendingOption::Physical])) {
            return response()->json(['error' => 'This delegate is not registered for physical/hybrid.'], 500);
        }

        if ($registration->payment_status != PaymentStatus::Paid && $registration->payment_status != PaymentStatus::Free) {
            return response()->json(['error' => 'This delegate has remaining balance. Please reach out to your local coordinator.'], 500);
        }

        $dates = $registration->bookings()->with(['slot'])->get()->pluck('slot');

        $booked_dates = array_column($dates->toArray(), 'event_date');

        return [
            'delegate' => $registration,
            'bookings' => $registration->bookings()->with(['slot'])->get(),
            'booked_dates' => $booked_dates,
            'attended' => !empty($registration->attendances()->where('slot_id', $slot_id)->first())
        ];
    }

    public function store(Event $event, Request $request)
    {
        $registration = Registration::where('uuid', $request->details['uuid'])->where('event_id', $event->id)->first();

        $slot_id = $registration->registration_type === 'Member' ? $event->active_member_slot_id : $event->active_guest_slot_id;

        $attendance = $registration->attendances->where('slot_id', $slot_id)->first();

        if (!$attendance) {
            return $registration->attendances()->create([
                'event_id' => $event->id,
                'slot_id' => $slot_id,
                'registration_type' => $registration->registration_type,
                'local_church' => $request->details['local_church'],
                'notes' => AttendanceType::Physical
            ]);
        }

        return $attendance;
    }

    public function export(Event $event) {
        return Excel::download(new ExportAttendance($event), 'attendance_' . TIME() . '.csv');
    }
}
