<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\RegistrationType;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Registration;
use App\Models\Event;
use App\Models\ReceivedHG;
use App\Models\Slots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Methods to bypass authentication.
     * Methods: Show
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => []]);
    }

    public function index(Event $event)
    {
        $color_assignment = config('settings.chart_color');

        $local_churches = array_keys(config('clustergroups'));
        $slots = $event->slots;

        $groupedSlots = $slots->groupBy('description')->map(function ($items) {
            return $items->pluck('id')->all();
        })->toArray();

        return view('dashboard.index', [
            'all' => (object) $this->get_local_churches_attendance($groupedSlots, $color_assignment, $local_churches),
            'members' => (object) $this->get_member_attendance($groupedSlots, $color_assignment, $local_churches),
            'guests' => (object) $this->get_guest_attendance($groupedSlots, $color_assignment, $local_churches),
            'trend' => (object) $this->get_all_attendance($groupedSlots),
            'progress' => (object) $this->get_attendance_progress($event),
            'received_hg' => (Array) $this->get_all_list_received_hg($groupedSlots),
            'guest_current_date' => Slots::where('id', $event->active_guest_slot_id)->first()->event_date,
            'member_current_date' => Slots::where('id', $event->active_member_slot_id)->first()->event_date,
            'event' => $event
        ]);
    }

    private function get_local_churches_attendance($groupedSlots, $color_assignment, $local_churches)
    {
        $data = [
            'data' => [
                'labels' => $local_churches,
                'datasets' => []
            ],
            'options' => [
                'responsive' => true
            ]
        ];

        foreach ($groupedSlots as $day => $slots) {
            $count = [];

            foreach ($local_churches as $local_church) {
                $attendance = Attendance::where('local_church', $local_church)->whereIn('slot_id', $slots)->count();

                $count[] = $attendance;
            }

            $data['data']['datasets'][] = [
                'label' => $day,
                'data' => $count,
                'backgroundColor' => $color_assignment[$day]['all'],
            ];
        }

        return $data;
    }

    private function get_member_attendance($groupedSlots, $color_assignment, $local_churches)
    {
        $data = [
            'data' => [
                'labels' => $local_churches,
                'datasets' => []
            ],
            'options' => [
                'responsive' => true
            ]
        ];

        foreach ($groupedSlots as $day => $slots) {
            $count = [];

            foreach ($local_churches as $local_church) {
                $attendance = Attendance::where('local_church', $local_church)->where('registration_type', RegistrationType::Member)->whereIn('slot_id', $slots)->count();

                $count[] = $attendance;
            }

            $data['data']['datasets'][] = [
                'label' => $day,
                'data' => $count,
                'backgroundColor' => $color_assignment[$day]['member'],
            ];
        }

        return $data;
    }

    private function get_guest_attendance($groupedSlots, $color_assignment, $local_churches)
    {
        $data = [
            'data' => [
                'labels' => $local_churches,
                'datasets' => []
            ],
            'options' => [
                'responsive' => true
            ]
        ];

        foreach ($groupedSlots as $day => $slots) {
            $count = [];

            foreach ($local_churches as $local_church) {
                $attendance = Attendance::where('local_church', $local_church)->where('registration_type', RegistrationType::Guest)->whereIn('slot_id', $slots)->count();

                $count[] = $attendance;
            }

            $data['data']['datasets'][] = [
                'label' => $day,
                'data' => $count,
                'backgroundColor' => $color_assignment[$day]['guest'],
            ];
        }

        return $data;
    }

    private function get_all_attendance($groupedSlots)
    {
        $data = [
            'labels' => array_keys($groupedSlots),
            'datasets' => [
                [
                    'label' => 'Member',
                    'backgroundColor' => '#87bc45',
                    'data' => []
                ],
                [
                    'label' => 'Guests',
                    'backgroundColor' => '#27aeef',
                    'data' => []
                ],
                [
                    'label' => 'Over all',
                    'backgroundColor' => '#b33dc6',
                    'data' => []
                ]
            ]
        ];

        foreach ($groupedSlots as $day => $slots) {
            $data['datasets'][0]['data'][] = Attendance::where('registration_type', RegistrationType::Member)->whereIn('slot_id', $slots)->count();
            $data['datasets'][1]['data'][] = Attendance::where('registration_type', RegistrationType::Guest)->whereIn('slot_id', $slots)->count();
            $data['datasets'][2]['data'][] = Attendance::whereIn('slot_id', $slots)->count();
        }

        return $data;
    }

    private function get_attendance_progress(Event $event)
    {
        $data = [];

        foreach (config('clustergroups') as $local_church => $clusters) {
            $actual_attendance = Attendance::where('local_church', $local_church)->whereIn('slot_id', [$event->active_guest_slot_id, $event->active_member_slot_id])->count();
            $expected_attendance = Booking::where('local_church', $local_church)->whereIn('slot_id', [$event->active_guest_slot_id, $event->active_member_slot_id])->where('status', BookingStatus::Confirmed)->count();

            $percentage = $expected_attendance === 0 ? 0 : ((3 / 500) * 100);
            $percentage = fmod($percentage, 1) !== 0.0 ? number_format($percentage, 2) : $percentage;
            $data[] = [
                'local_church' => $local_church,
                'percentage' => $percentage,
                'actual_attendance' => $actual_attendance,
                'expected_attendance' => $expected_attendance
            ];
        }

        return $data;
    }

    public function get_all_list_received_hg($groupedSlots) {
        $allotments = $groupedSlots;
        $data = [];

        foreach ($allotments as $day => $allotment) {
            $member_slot_details = Slots::where('id', $allotment[0])->first();
            $guest_slot_details = Slots::where('id', $allotment[1])->first();

            $member = ReceivedHG::where('date_received', $member_slot_details['event_date']->toDateString())->where('registration_type', 'Member')->get();
            $guest =  ReceivedHG::where('date_received', $guest_slot_details['event_date']->toDateString())->where('registration_type', 'Guest')->get();

            $collection = [
                'day' => $day,
                'member' => [
                    'data' => $member,
                    'count' => $member->count(),
                ],
                'guest' => [
                    'data' => $guest,
                    'count' => $guest->count()
                ],
                'local_churches' => []
            ];

            $local_churches = explode(',', env('LOCAL_CHURCHES'));
            foreach ($local_churches as $lc) {
                $member_slot_details = Slots::where('id', $allotment[0])->first();
                $guest_slot_details = Slots::where('id', $allotment[1])->first();

                $lc_member = ReceivedHG::where('date_received', $member_slot_details['event_date']->toDateString())->where('registration_type', 'Member')->where('local_church', $lc)->get();
                $lc_guest =  ReceivedHG::where('date_received', $guest_slot_details['event_date']->toDateString())->where('registration_type', 'Guest')->where('local_church', $lc)->get();

                $collection['local_churches'][] = [
                    'local_church' => $lc,
                    'member' => [
                        'data' => $lc_member,
                        'count' => $lc_member->count(),
                    ],
                    'guest' => [
                        'data' => $lc_guest,
                        'count' => $lc_guest->count()
                    ]
                ];
            }

            $data[] = $collection;
        }
        
        return $data;
    }

    // views
    public function view_attendance_per_church(Event $event, Request $request)
    {
        if ($request->local_church) {
            $attendance = Attendance::where('local_church', $request->local_church);
        }

        $slots = $event->slots;

        $groupedSlots = $slots->groupBy('description')->map(function ($items) {
            return $items->pluck('id')->all();
        });

        if ($request->awta_day) {
            if ($request->local_church) {
                $attendance = $attendance->whereIn('slot_id', $groupedSlots[$request->awta_day]);
            } else {
                $attendance = Attendance::whereIn('slot_id', $groupedSlots[$request->awta_day]);
            }
        }

        $attendance = $attendance->pluck('registration_id');

        $booking = Booking::with('registration');

        if ($request->awta_day) {
            $booking = $booking->whereIn('slot_id', $groupedSlots[$request->awta_day]);
        }

        $booking = $booking->whereHas('registration', function ($query) use ($request) {
            return $query->where('fullname', 'LIKE', "%$request->keyword%")
                ->orWhere('uuid', $request->keyword);
        })
        ->where('status', BookingStatus::Confirmed);

        if ($request->local_church) {
            $booking = $booking->where('local_church', $request->local_church);
        }

        if ($request->registration_type) {
            $booking = $booking->whereHas('registration', function ($query) use ($request) {
                return $query->where('registration_type', $request->registration_type);
            });
        }

        if ($request->attendance) {
            if ($request->attendance === 'Present') {
                $booking = $booking->whereIn('registration_id', $attendance->toArray());
            }

            if ($request->attendance === 'Not Yet Present') {
                $booking = $booking->whereNotIn('registration_id', $attendance->toArray());
            }
        }

        $booking = $booking->paginate(10);

        $booking->getCollection()->transform(function ($value) use ($attendance) {
            // Your code here
            $value->attendance = in_array($value->registration_id, $attendance->toArray()) ? 'Present' : 'Not Yet Present';

            return $value;
        });

        return view('dashboard.attendance', [
            'absents' => $booking,
            'event' => $event,
            'days' => $event->slots->groupBy('description')
        ]);
    }

    public function view_received_hg_per_church(Event $event, Request $request) {
        $received_hg = ReceivedHG::with('registration', 'slot')->where('event_id', $event->id);

        $slots = $event->slots;

        $groupedSlots = $slots->groupBy('description')->map(function ($items) {
            return $items->pluck('id')->all();
        });

        if ($request->awta_day) {
            $received_hg = $received_hg->whereIn('slot_id', $groupedSlots[$request->awta_day]);
        }

        if ($request->local_church) {
            $received_hg = $received_hg->where('local_church', $request->local_church);
        }

        $received_hg = $received_hg->whereHas('registration', function ($query) use ($request) {
            return $query->where('fullname', 'LIKE', "%$request->keyword%")
                ->orWhere('uuid', $request->keyword);
        });

        if ($request->registration_type) {
            $received_hg = $received_hg->whereHas('registration', function ($query) use ($request) {
                return $query->where('registration_type', $request->registration_type);
            });
        }

        $received_hg = $received_hg;

        return view('dashboard.received_hg', [
            'data' => $received_hg->get(),
            'event' => $event,
            'days' => $event->slots->groupBy('description')
        ]);
    }
}
