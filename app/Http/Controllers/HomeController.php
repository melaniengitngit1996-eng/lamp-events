<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Resources\RegistrationResource;
use App\Models\LookUp;
use App\Models\Event;
use App\Models\Slots;
use App\Models\Attendance;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'events');
    }

    /**
     * Show the registration dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('site.landing');
    }

    /**
     * Show the registration dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function events()
    {
        return view('site.events');
    }

    public function show(Event $event, Request $request)
    {
        $slots_members = Slots::where('registration_type', 'Member')
            ->where('event_id', $event->id)
            ->with('bookings')
            ->get()
            ->map(function ($slot) {
                $booked = $slot->bookings->groupBy('local_church')->map(function ($lc) {
                    return $lc->count();
                });

                $slot['booked_per_church'] = $booked;

                return $slot;
            });

        $slots_guests = Slots::where('registration_type', 'Guest')->where('event_id', $event->id)->with('bookings')->get()->map(function ($slot) {
            $booked = $slot->bookings->groupBy('local_church')->map(function ($lc) {
                return $lc->count();
            });

            $slot['booked_per_church'] = $booked;

            return $slot;
        });

        $local_churches = explode(',', env('LOCAL_CHURCHES'));

        $slots = Slots::where('registration_type', 'Member')->where('event_id', $event->id)->get();
        $attendance_count = [];

        foreach ($slots as $slot) {
            $slot = (object) $slot;
            $count = [];

            $member = Slots::where('event_date', $slot['event_date'])->where('registration_type', 'Member')->first();
            $guest = Slots::where('event_date', $slot['event_date'])->where('registration_type', 'Guest')->first();

            $guest_overall_total = 0;
            $guest_overall_attended = 0;
            $member_overall_total = 0;
            $member_overall_attended = 0;

            foreach ($local_churches as $local_church) {
                $array = [];

                // count member
                $member_total_confirmed = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $member->id)
                    ->where('status', BookingStatus::Confirmed)
                    ->count();

                $member_total_canceled = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $member->id)
                    ->where('status', BookingStatus::Cancelled)
                    ->count();

                $member_total_pending = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $member->id)
                    ->where('status', BookingStatus::Pending)
                    ->count();
                // --------------

                $member_attended = DB::table('attendances')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $member->id)
                    ->count();

                // count guest
                $guest_total_confirmed = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $guest->id)
                    ->where('status', BookingStatus::Confirmed)
                    ->count();

                $guest_total_canceled = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $guest->id)
                    ->where('status', BookingStatus::Cancelled)
                    ->count();

                $guest_total_pending = DB::table('bookings')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $guest->id)
                    ->where('status', BookingStatus::Pending)
                    ->count();

                $guest_attended = DB::table('attendances')
                    ->where('local_church', $local_church)
                    ->where('slot_id', $guest->id)
                    ->count();

                $guest_overall_total += $guest_total_confirmed;
                $guest_overall_attended += $guest_attended;

                $member_overall_total += $member_total_confirmed;
                $member_overall_attended += $member_attended;

                $array['local_church'] = $local_church;
                $array['count'] = array(
                    'member' => array(
                        'total' => $member_total_confirmed,
                        'attended' => $member_attended,
                        'pending' => $member_total_pending,
                        'cancelled' => $member_total_canceled
                    ),
                    'guest' => array(
                        'total' => $guest_total_confirmed,
                        'attended' => $guest_attended,
                        'pending' => $guest_total_pending,
                        'cancelled' => $guest_total_canceled
                    )
                );

                array_push($count, $array);
            }

            $slot['count'] = $count;
            $slot['overall'] = [
                'member' => [
                    'attended' => $member_overall_attended,
                    'total' => $member_overall_total
                ],
                'guest' => [
                    'attended' => $guest_overall_attended,
                    'total' => $guest_overall_total
                ]
            ];

            $slot['event_date'] = date_format($slot['event_date'], 'F d');

            array_push($attendance_count, $slot);
        }

        $overall = [];

        foreach ($local_churches as $local_church) {
            $count = [];
            $count['local_church'] = $local_church;
            $count['count'] = [
                'member' => [
                    'attended' => DB::table('attendances')
                        ->whereIn('slot_id', array(1, 2, 3, 4))
                        ->where('local_church', $local_church)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'total' => DB::table('bookings')
                        ->whereIn('slot_id', array(1, 2, 3, 4))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Confirmed)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'cancelled' => DB::table('bookings')
                        ->whereIn('slot_id', array(1, 2, 3, 4))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Cancelled)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'pending' => DB::table('bookings')
                        ->whereIn('slot_id', array(1, 2, 3, 4))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Pending)
                        ->count(DB::raw('DISTINCT registration_id'))
                ],
                'guest' => [
                    'attended' => DB::table('attendances')
                        ->whereIn('slot_id', array(5, 6, 7, 8))
                        ->where('local_church', $local_church)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'total' => DB::table('bookings')
                        ->whereIn('slot_id', array(5, 6, 7, 8))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Confirmed)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'cancelled' => DB::table('bookings')
                        ->whereIn('slot_id', array(5, 6, 7, 8))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Cancelled)
                        ->count(DB::raw('DISTINCT registration_id')),
                    'pending' => DB::table('bookings')
                        ->whereIn('slot_id', array(5, 6, 7, 8))
                        ->where('local_church', $local_church)
                        ->where('status', BookingStatus::Pending)
                        ->count(DB::raw('DISTINCT registration_id'))
                ]
            ];

            $overall[] = $count;
        }

        $overall_total = [
            'member' => [
                'attended' => DB::table('attendances')
                    ->whereIn('slot_id', array(1, 2, 3, 4))
                    ->count(DB::raw('DISTINCT registration_id')),
                'total' => DB::table('bookings')
                    ->whereIn('slot_id', array(1, 2, 3, 4))
                    ->where('status', BookingStatus::Confirmed)
                    ->count(DB::raw('DISTINCT registration_id')),
                'cancelled' => DB::table('bookings')
                    ->whereIn('slot_id', array(1, 2, 3, 4))
                    ->where('status', BookingStatus::Cancelled)
                    ->count(DB::raw('DISTINCT registration_id')),
                'pending' => DB::table('bookings')
                    ->whereIn('slot_id', array(1, 2, 3, 4))
                    ->where('status', BookingStatus::Pending)
                    ->count(DB::raw('DISTINCT registration_id'))
            ],
            'guest' => [
                'attended' => DB::table('attendances')
                    ->whereIn('slot_id', array(5, 6, 7, 8))
                    ->count(DB::raw('DISTINCT registration_id')),
                'total' => DB::table('bookings')
                    ->whereIn('slot_id', array(5, 6, 7, 8))
                    ->where('status', BookingStatus::Confirmed)
                    ->count(DB::raw('DISTINCT registration_id')),
                'pending' => DB::table('bookings')
                    ->whereIn('slot_id', array(5, 6, 7, 8))
                    ->where('status', BookingStatus::Pending)
                    ->count(DB::raw('DISTINCT registration_id')),
                'cancelled' => DB::table('bookings')
                    ->whereIn('slot_id', array(5, 6, 7, 8))
                    ->where('status', BookingStatus::Cancelled)
                    ->count(DB::raw('DISTINCT registration_id')),
            ]
        ];

        // set tab value
        $tab = 0;
        if ($request->type === 'registration') $tab = 0;
        if ($request->type === 'lookup') $tab = 1;
        if ($request->type === 'attendance') $tab = 2;
        if ($request->type === 'bookings') $tab = 3;
        if ($request->type === 'attendance_count') $tab = 4;
        if ($request->type === 'slots') $tab = 5;
        if ($request->type === 'received_hg') $tab = 6;

        return view('home', [
            'event' => $event,
            'search' => $request->search,
            'type' => $request->type,
            'slots' => [
                'members' => $slots_members,
                'guests' => $slots_guests
            ],
            'slots_list' => json_encode(Slots::where('event_id', $event->id)->get()),
            'count' => json_encode($attendance_count),
            'overall' => json_encode($overall),
            'overall_total' => json_encode($overall_total),
            'tab' => $tab
        ]);
    }
}
