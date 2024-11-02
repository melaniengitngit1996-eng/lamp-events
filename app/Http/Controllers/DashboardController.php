<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\RegistrationType;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\Registration;
use App\Models\ReceivedHG;
use App\Models\Slots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $color_assignment = config('settings.chart_color');

        $local_churches = array_keys(config('clustergroups'));

        return view('dashboard', [
            'all' => (object) $this->get_local_churches_attendance($color_assignment, $local_churches),
            'members' => (object) $this->get_member_attendance($color_assignment, $local_churches),
            'guests' => (object) $this->get_guest_attendance($color_assignment, $local_churches),
            'trend' => (object) $this->get_all_attendance(),
            'progress' => (object) $this->get_attendance_progress(),
            'received_hg' => (Array) $this->get_all_list_received_hg(),
            'guest_current_date' => Slots::where('id', env('SLOT_ID_TODAY_GUEST'))->first()->event_date,
            'member_current_date' => Slots::where('id', env('SLOT_ID_TODAY_MEMBER'))->first()->event_date
        ]);
    }

    private function get_local_churches_attendance($color_assignment, $local_churches)
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

        foreach (config('settings.slots_allotment') as $day => $slots) {
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

    private function get_member_attendance($color_assignment, $local_churches)
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

        foreach (config('settings.slots_allotment') as $day => $slots) {
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

    private function get_guest_attendance($color_assignment, $local_churches)
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

        foreach (config('settings.slots_allotment') as $day => $slots) {
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

    private function get_all_attendance()
    {
        $data = [
            'labels' => array_keys(config('settings.slots_allotment')),
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

        foreach (config('settings.slots_allotment') as $day => $slots) {
            $data['datasets'][0]['data'][] = Attendance::where('registration_type', RegistrationType::Member)->whereIn('slot_id', $slots)->count();
            $data['datasets'][1]['data'][] = Attendance::where('registration_type', RegistrationType::Guest)->whereIn('slot_id', $slots)->count();
            $data['datasets'][2]['data'][] = Attendance::whereIn('slot_id', $slots)->count();
        }

        return $data;
    }

    private function get_attendance_progress()
    {
        $data = [];

        foreach (config('clustergroups') as $local_church => $clusters) {
            $actual_attendance = Attendance::where('local_church', $local_church)->whereIn('slot_id', [config('settings.guest_slot_today'), config('settings.member_slot_today')])->count();
            $expected_attendance = Booking::where('local_church', $local_church)->whereIn('slot_id', [config('settings.guest_slot_today'), config('settings.member_slot_today')])->where('status', BookingStatus::Confirmed)->count();

            $percentage = $expected_attendance === 0 ? 0 : (($actual_attendance / $expected_attendance) * 100);
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

    public function get_all_list_received_hg() {
        $allotments = config('settings.slots_allotment');
        $data = [];

        foreach ($allotments as $day => $allotment) {
            $member = ReceivedHG::with('slot')->where('slot_id', $allotment[0])->get();
            $guest = ReceivedHG::with('slot')->where('slot_id', $allotment[1])->get();

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
                $lc_member = ReceivedHG::with('slot')->where('slot_id', $allotment[0])->where('local_church', $lc)->get();
                $lc_guest = ReceivedHG::with('slot')->where('slot_id', $allotment[1])->where('local_church', $lc)->get();

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
    public function view_attendance_per_church(Request $request)
    {
        // dd($request->awta_day);
        $attendance = Attendance::where('local_church', $request->local_church);

        if ($request->awta_day) {

            $attendance = $attendance->whereIn('slot_id', config('settings.slots_allotment')[$request->awta_day]);
        }

        $attendance = $attendance->pluck('registration_uuid');

        $booking = Booking::with('registration');

        if ($request->awta_day) {
            $booking = $booking->whereIn('slot_id', config('settings.slots_allotment')[$request->awta_day]);
        }

        $booking = $booking->whereHas('registration', function ($query) use ($request) {
            return $query->where('fullname', 'LIKE', "%$request->keyword%")
                ->orWhere('uuid', $request->keyword);
        })
            ->where('status', BookingStatus::Confirmed)
            ->where('local_church', $request->local_church);

        if ($request->registration_type) {
            $booking = $booking->whereHas('registration', function ($query) use ($request) {
                return $query->where('registration_type', $request->registration_type);
            });
        }

        if ($request->attendance) {
            if ($request->attendance === 'Present') {
                $booking = $booking->whereIn('registration_uuid', $attendance->toArray());
            }

            if ($request->attendance === 'Not Yet Present') {
                $booking = $booking->whereNotIn('registration_uuid', $attendance->toArray());
            }
        }

        $booking = $booking->paginate(10);

        $booking->getCollection()->transform(function ($value) use ($attendance) {
            // Your code here
            $value->attendance = in_array($value->registration_uuid, $attendance->toArray()) ? 'Present' : 'Not Yet Present';

            return $value;
        });

        return view('dashboard.attendance', [
            'absents' => $booking
        ]);
    }

    public function view_received_hg_per_church(Request $request) {
        $received_hg = ReceivedHG::with('registration', 'slot');

        if ($request->awta_day) {
            $received_hg = $received_hg->whereIn('slot_id', config('settings.slots_allotment')[$request->awta_day]);
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

        $received_hg = $received_hg->paginate(10);

        return view('dashboard.received_hg', [
            'data' => $received_hg
        ]);
    }
}
