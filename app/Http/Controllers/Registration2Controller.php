<?php

namespace App\Http\Controllers;

use App\Enums\AttendingOption;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationType;
use App\Exports\ExportRegistration;
use App\Models\Attendance;
use App\Models\Booking;
use App\Models\LookUp;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Slots;
use App\Models\UUID;
use App\Models\Event;
use App\Notifications\Registered;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AvailableUuid;

class Registration2Controller extends Controller
{
    /**
     * list all registrations
     *
     * @param  String $slug
     */
    public function index(Request $request)
    {
        $search = json_decode($request->search);

        $registration = Registration::withSum('payments', 'amount')->with('event');

        if ($search->payment_status) {
            $registration = $registration->where('payment_status', '=', $search->payment_status);
        }

        if ($search->booking_status) {
            $registration = $registration->where('booking_status', '=', $search->booking_status);
        }

        if ($search->registration_type) {
            $registration = $registration->where('registration_type', '=', $search->registration_type);
        }

        if ($search->attending_option) {
            $registration = $registration->where('attending_option', '=', $search->attending_option);
        }

        if ($search->category) {
            $registration = $registration->where('category', '=', $search->category);
        }

        if ($search->local_church) {
            $registration = $registration->where('local_church', '=', $search->local_church);
        }

        if ($search->keyword) {
            $registration = $registration->where('fullname', 'LIKE', "%$search->keyword%")
                ->orWhere('uuid', 'LIKE', "%$search->keyword%");
        }

        $registration = $registration->paginate(10);

        $registration->map(function ($item) {
            $booked_dates = $item->bookings()->with('slot')->get()->toArray();

            $item->booked_dates = array_map(function ($date) {
                return $date['slot']['event_date'];
            }, $booked_dates);

            $attended_dates = $item->attendances()->with('slot')->get()->toArray();

            $item->attended_dates = array_map(function ($date) {
                return $date['slot']['event_date'];
            }, $attended_dates);
        });

        return $registration;
    }

    /**
     * show dynamic registration form
     *
     * @param  String $slug
     */
    public function create(Event $event) {
        if (empty($event)) {
            abort(404);
        }

        if ($event->close_registration) {
            return view('registration.closed');
        }

        $directory = "registration.{$event->template_id}.create";
        
        return view($directory, [
            'event' => $event,
            'slots' => [
                'member' => $event->slots()->where('registration_type', RegistrationType::Member)->get(),
                'guest' => $event->slots()->where('registration_type', RegistrationType::Guest)->get()
            ]
        ]);
    }

    /**
     * show registration ticket
     *
     * @param  String $slug
     */
    public function show(Event $event, Request $request)
    {
        if (empty($event)) {
            abort(404);
        }

        $uuid = explode(',', $request->id);

        $registration = (array) Registration::with('bookings', 'bookings.slot', 'additional_data', 'lookup')->where('event_id', $event->id)->whereIn('uuid', $uuid)->get()->toArray();

        $registration = array_map(function ($data) {
            $data['booked_dates'] = array_map(function ($dates) {
                return $dates['slot']['event_date'];
            }, $data['bookings']);
            $data['avail_new_lamp_id'] = $data['lookup']['avail_new_lamp_id'] ?? NULL;
            $data['has_viewed_ticket'] = $data['additional_data']['has_viewed_ticket'] ?? NULL;

            return $data;
        }, $registration);
        
        return view('registration.show', [
            'registration' => $registration,
            'event' => $event
        ]);
    }

    /**
     * Store a newly created registration in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Event $event, Request $request)
    {
        // member registration
        if ($request->step_1['registrationType'] === 'Member') {
            $uuid = null;

            switch ($request->step_1['withAwtaCard']) {
                case 'none': // None
                    $details = array_merge($request->step_1, $request->step_2, $request->step_3);

                    $uuid = UUID::issue();
                    $email = $details['email'];
                    $firstname = $details['firstName'];
                    $lastname = $details['lastName'];
                    $fullname = $details['firstName'] . ' ' . $details['lastName'];
                    $facebook = $details['facebookName'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['localChurch'];
                    $country = $details['country'];
                    $category = $details['category'];
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = config('settings.member_booking_limit');
                    $awta_card_number = '--';
                    break;

                case 'lost': // Yes, but I don’t have it.
                    $details = array_merge($request->step_1, $request->step_2, $request->step_3);

                    $lookup = LookUp::where('lamp_id', $details['selected'])->first();

                    $uuid = is_null($lookup['old_lamp_card_number']) ? UUID::issue() : $lookup['lamp_id'];
                    $email = $details['email'];
                    $firstname = $lookup['firstname'];
                    $lastname = $lookup['lastname'];
                    $fullname = $lookup['firstname'] . ' ' . $lookup['lastname'];
                    $facebook = $lookup['facebook_name'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['localChurch'];
                    $country = $details['country'];
                    $category = $details['category'];
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $awta_card_number = $details['selected'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $lookup['can_book_days'];
                    break;

                    case 'mislaid': // Yes, but I don’t have it.
                        $details = array_merge($request->step_1, $request->step_2, $request->step_3);
    
                        $lookup = LookUp::where('lamp_id', $details['selected'])->first();
    
                        $uuid = is_null($lookup['old_lamp_card_number']) ? UUID::issue() : $lookup['lamp_id'];
                        $email = $details['email'];
                        $firstname = $lookup['firstname'];
                        $lastname = $lookup['lastname'];
                        $fullname = $lookup['firstname'] . ' ' . $lookup['lastname'];
                        $facebook = $lookup['facebook_name'];
                        $registration_type = $details['registrationType'];
                        $local_church = $details['localChurch'];
                        $country = $details['country'];
                        $category = $details['category'];
                        $attending_option = $details['attendingOption'];
                        $with_awta_card = $details['withAwtaCard'];
                        $cluster_group = $details['clusterGroup'];
                        $awta_card_number = $details['selected'];
                        $assistance = $details['specificMedicalAssistance'];
                        $can_book_days = $lookup['can_book_days'];
                        break;

                case 'yes': // Yes, I still have it.
                    $details = array_merge($request->step_1, $request->step_3);

                    $uuid = is_null($details['found']['oldlampIDNumber']) ? UUID::issue() : $details['lampIDNumber'];
                    $email = $details['email'];
                    $firstname = $details['found']['firstName'];
                    $lastname = $details['found']['lastName'];
                    $fullname = $details['found']['firstName'] . ' ' . $details['found']['lastName'];
                    $facebook = $details['found']['facebookName'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['found']['localChurch'];
                    $country = $details['found']['country'];
                    $category = $details['found']['category'];
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $awta_card_number = $details['lampIDNumber'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $details['found']['canBookDays'];
                    break;
            }

            $registration = Registration::create([
                'uuid' => strtoupper($uuid),
                'event_id' => $event->id,
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'fullname' => $fullname,
                'facebook_name' => $facebook,
                'registration_type' => $registration_type,
                'local_church' => $local_church,
                'cluster_group' => $cluster_group,
                'country' => $country,
                'category' => $category,
                'attending_option' => $attending_option,
                'with_awta_card' => $with_awta_card,
                'medical_assistance_needed' => $assistance,
                'can_book_days' => $can_book_days,
                'notes' => [],
                'activities' => [],
                'booking_activities' => []
            ]);

            $registration->additional_data()->create([
                'registration_id' => $registration->id,
                'has_viewed_ticket' => NULL
            ]);

            $lookup = LookUp::where('lamp_id', $awta_card_number)->first();

            // checking if the member is in the master list
            if ($lookup) {
                $update = [
                    'is_registered' => true
                ];

                if (is_null($lookup['old_lamp_card_number'])) {
                    $update['lamp_id'] =  strtoupper($registration->uuid);
                    $update['old_lamp_card_number'] = strtoupper($lookup->lamp_id);
                }
                // setting new LAMP ID number
                $lookup->update($update);
            } else {
                // insert member to master list if not existing
                LookUp::create([
                    'lamp_id' => strtoupper($registration->uuid),
                    'old_lamp_card_number' => strtoupper($awta_card_number),
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'fullname' => $firstname . ' ' . $lastname,
                    'facebook_name' => $facebook,
                    'registration_type' => 'Member',
                    'category' => $category,
                    'local_church' => $local_church,
                    'country' => $country,
                    'can_book_days' => config('settings.member_booking_limit'),
                    'is_registered' => true
                ]);
            }

            if ($attending_option === AttendingOption::Hybrid) {
                $this->book($registration, $request->step_3['booked']);
            }

            $registration = $this->updatePaymentStatus($registration->id, false);

            // if ($registration->attending_option === AttendingOption::Hybrid) {
            $this->notify($registration->id);
            // }

            return $registration->uuid;
        } else { // guest registration
            if (true === env('ONLINE_GUESTS_GROUP_REGISTRATION', true)) {
                $registered = [];

                foreach ($request->step_2['guests'] as $key => $value) {
                    $uuid = $this->generateGuestId();

                    $details = (object) $value;

                    $registration = Registration::create([
                        'uuid' => $uuid,
                        'event_id' => $event->id,
                        'email' => $details->email,
                        'firstname' => $details->firstName,
                        'lastname' => $details->lastName,
                        'fullname' => $details->firstName . ' ' . $details->lastName,
                        'facebook_name' => $details->facebookName,
                        'registration_type' => 'Guest',
                        'local_church' => $details->localChurch,
                        'cluster_group' => $details->clusterGroup,
                        'country' => $details->country,
                        'category' => PaymentStatus::Free,
                        'attending_option' => $request->step_1['attendingOption'],
                        'medical_assistance_needed' => $details->specificMedicalAssistance,
                        'with_awta_card' => 'none',
                        'notes' => [],
                        'activities' => [],
                        'booking_activities' => []
                    ]);

                    $this->book($registration, $details->booked);

                    $registration = $this->updatePaymentStatus($registration->id, false);

                    $registered[] = $registration->uuid;
                }

                return $registered;
            } else {
                $uuid = $this->generateGuestId();

                $details = (object) $request->step_2;

                $registration = Registration::create([
                    'uuid' => $uuid,
                    'event_id' => $event->id,
                    'email' => $details->email,
                    'firstname' => $details->firstName,
                    'lastname' => $details->lastName,
                    'fullname' => $details->firstName . ' ' . $details->lastName,
                    'facebook_name' => $details->facebookName,
                    'registration_type' => 'Guest',
                    'local_church' => $details->localChurch,
                    'cluster_group' => $details->clusterGroup,
                    'country' => $details->country,
                    'category' => PaymentStatus::Free,
                    'attending_option' => AttendingOption::Online,
                    'with_awta_card' => 'none',
                    'notes' => [],
                    'activities' => [],
                    'booking_activities' => []
                ]);

                $registration = $this->updatePaymentStatus($registration->id, false);

                return $registration->uuid;
            }
        }
    }

    /**
     * View edit page for registration
     *
     * @param $id
     */
    public function edit($registration_id)
    {
        return view('registration.edit', [
            'registration' => Registration::with('lookup')->where('id', $registration_id)->first()
        ]);
    }

    public function destroy(Registration $registration)
    {
        $registration->bookings()->delete();

        $registration->payments()->delete();

        $registration->attendances()->delete();

        return $registration->delete();
    }
}
