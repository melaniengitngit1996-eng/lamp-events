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
use App\Models\Category;

class Registration2Controller extends Controller
{
    public function __construct()
    {
        $auth_exeptions = ['validation', 'store', 'show', 'update', 'create'];

        $this->middleware('auth', ['except' => $auth_exeptions]);
    }

    /**
     * list all registrations
     *
     * @param Event $event
     * @param Request $request
     */
    public function index(Event $event, Request $request)
    {
        $search = json_decode($request->search);

        $registration = Registration::withSum('payments', 'amount')->with('event')->where('event_id', $event->id);

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

        if ($search->keyword && (stripos($search->keyword, "LAMP") !== false || stripos($search->keyword, "GUEST") !== false)) {
            $registration = $registration->Where('uuid', 'LIKE', "%$search->keyword%");
        } else {
            $registration = $registration->where('fullname', 'LIKE', "%$search->keyword%");
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
     * @param Event $event
     * @param Request $request
     */
    public function create(Event $event, Request $request)
    {
        if (empty($event)) {
            abort(404);
        }

        // add ?admin=yesplease to force register
        if (!$request->admin) {
            if ($event->close_registration) {
                return view('registration.closed', [
                    'event' => $event
                ]);
            }
        }

        if ($event->is_maintenance) {
            return view('registration.maintenance', [
                'event' => $event
            ]);
        }

        $directory = "registration.{$event->template_id}.create";
        $event = Event::with(['custom_fields', 'venues'])->find($event->id);

        return view($directory, [
            'event' => $event,
            'slots' => [
                'member' => $event->slots()
                    ->with('localChurchSlots')
                    ->where('registration_type', RegistrationType::Member)
                    ->get(),

                'guest' => $event->slots()
                    ->with('localChurchSlots')
                    ->where('registration_type', RegistrationType::Guest)
                    ->get(),
            ]
        ]);
    }

    /**
     * show registration ticket
     *
     * @param Event $event
     * @param Request $request
     */
    public function show(Event $event, Request $request)
    {
        if (empty($event)) {
            abort(404);
        }

        $uuid = explode(',', $request->id);

        $registration = (array) Registration::with('bookings', 'bookings.slot', 'additional_data', 'lookup')->where('event_id', $event->id)->whereIn('uuid', $uuid)->get()->toArray();

        $registration = array_map(function ($data) use ($event) {
            $data['booked_dates'] = array_map(function ($dates) use ($event) {
                if ($event->has_multiple_venues) {
                    return [
                        'event_date' => $dates['slot']['event_date'],
                        'venue' => $dates['venue']
                    ];
                } else {
                    return $dates['slot']['event_date'];
                }
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
     * @param Event $event
     * @param Request $request
     */
    public function store(Event $event, Request $request)
    {
        $event = Event::with(['custom_fields', 'slots'])->find($event->id);

        // member registration
        if ($request->step_1['registrationType'] === 'Member') {
            $uuid = null;

            $details = array_merge($request->step_1, $request->step_2, $request->step_3);

            $custom_fields_value = $this->getCustomFieldsValue($event->custom_fields, $details);

            // hard coded
            if ($event->slug == 1226292026 && $custom_fields_value['venue'] == 'Calamba Tent') {
                $remaining = $event->slots->where('registration_type', 'Member')->first()->available ?? 0;

                if ($remaining <= 0) {
                    return response()->json(['error' => 'Sorry, there are no remaining slots for Calamba Tent. Please select another venue to continue.'], 500);
                }
            }

            switch ($request->step_1['withAwtaCard']) {
                case 'none': // None
                    $uuid = UUID::issue($event);
                    $email = $details['email'];
                    $firstname = $details['firstName'];
                    $lastname = $details['lastName'];
                    $fullname = $details['firstName'] . ' ' . $details['lastName'];
                    $facebook = $details['facebookName'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['localChurch'];
                    $country = $details['country'];
                    $category = Category::getByBirthdate($details['birthdate'])?->name;
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $event->member_booking_limit;
                    $awta_card_number = '--';
                    $birthdate = $details['birthdate'];
                    break;

                case 'lost': // Yes, but I don’t have it.
                    $lookup = LookUp::where('lamp_id', $details['selected'])->first();

                    $uuid = is_null($lookup['old_lamp_card_number']) ? UUID::issue($event) : $lookup['lamp_id'];
                    $email = $details['email'];
                    $firstname = $lookup['firstname'];
                    $lastname = $lookup['lastname'];
                    $fullname = $lookup['firstname'] . ' ' . $lookup['lastname'];
                    $facebook = $lookup['facebook_name'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['localChurch'];
                    $country = $details['country'];
                    $category = Category::getByBirthdate($details['birthdate'])?->name;
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $awta_card_number = $details['selected'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $lookup['can_book_days'];
                    $birthdate = $details['birthdate'];
                    break;

                case 'mislaid': // Yes, but I don’t have it.    
                    $lookup = LookUp::where('lamp_id', $details['selected'])->first();

                    $uuid = is_null($lookup['old_lamp_card_number']) ? UUID::issue($event) : $lookup['lamp_id'];
                    $email = $details['email'];
                    $firstname = $lookup['firstname'];
                    $lastname = $lookup['lastname'];
                    $fullname = $lookup['firstname'] . ' ' . $lookup['lastname'];
                    $facebook = $lookup['facebook_name'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['localChurch'];
                    $country = $details['country'];
                    $category = Category::getByBirthdate($details['birthdate'])?->name;
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $awta_card_number = $details['selected'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $lookup['can_book_days'];
                    $birthdate = $details['birthdate'];
                    break;

                case 'yes': // Yes, I still have it.
                    $uuid = is_null($details['found']['oldlampIDNumber']) ? UUID::issue($event) : $details['lampIDNumber'];
                    $email = $details['email'];
                    $firstname = $details['found']['firstName'];
                    $lastname = $details['found']['lastName'];
                    $fullname = $details['found']['firstName'] . ' ' . $details['found']['lastName'];
                    $facebook = $details['found']['facebookName'];
                    $registration_type = $details['registrationType'];
                    $local_church = $details['found']['localChurch'];
                    $country = $details['found']['country'];
                    $category = Category::getByBirthdate($details['birthdate'])?->name;
                    dd($details['birthdate'], $category);
                    $attending_option = $details['attendingOption'];
                    $with_awta_card = $details['withAwtaCard'];
                    $cluster_group = $details['clusterGroup'];
                    $awta_card_number = $details['lampIDNumber'];
                    $assistance = $details['specificMedicalAssistance'];
                    $can_book_days = $details['found']['canBookDays'];
                    $birthdate = $details['birthdate'];
                    break;
            }

            // ------------------------- custom blocks -------------------------------
            $this->addCampingData($category, $request->step_1['withAwtaCard'], $details, $event, 'Member');
            // ------------------------- custom block ends here ----------------------

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
                'booking_activities' => [],
                'custom_fields' => $custom_fields_value
            ]);

            $registration->additional_data()->create([
                'registration_id' => $registration->id,
                'has_viewed_ticket' => NULL
            ]);

            // $registration = Registration::where('uuid', $awta_card_number)->first();

            $lookup = LookUp::where('lamp_id', $awta_card_number)->first();

            // checking if the member is in the master list
            if ($lookup) {
                $update = [
                    'cluster_group' => $cluster_group,
                    'email' => $email,
                    'birthdate' => $birthdate,
                    'category' => $category
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
                    'can_book_days' => $event->member_booking_limit,
                    'cluster_group' => $cluster_group,
                    'birthdate' => $birthdate
                ]);
            }

            if ($attending_option != AttendingOption::Online) {
                $booked = $request->step_3['booked'] ?? null;

                if (
                    $event->slug == 1226292026 &&
                    AttendingOption::Physical === $registration->attending_option &&
                    RegistrationType::Member === $registration->registration_type
                ) {
                    $slots = Slots::where('event_id', $event->id)->where('registration_type', $registration->registration_type)->get();

                    $booked = [];

                    foreach ($slots as $slot) {
                        $booked[$slot->id] = $custom_fields_value['venue'];
                    }
                }

                if ($booked != null) {
                    $this->book($event, $registration, $booked);
                }
            }

            $registration = $this->updatePaymentStatus($registration->id, true);

            // if (in_array($attending_option, [AttendingOption::Hybrid, AttendingOption::Physical])) {
            // $this->notify($registration->id);
            // }

            return $registration->uuid;
        } else { // guest registration
            $details = $request->step_1;

            $custom_fields_value = $this->getCustomFieldsValue($event->custom_fields, $details);

            if (true === env('ONLINE_GUESTS_GROUP_REGISTRATION', true)) {
                $registered = [];

                foreach ($request->step_2['guests'] as $key => $value) {
                    $uuid = $this->generateGuestId();

                    $details = (object) $value;

                    $book = $details->booked;

                    // Auto-book even if event booking is disabled
                    // Ensures a booking record exists for proper attendance tracking
                    if (
                        !$event->with_booking &&
                        AttendingOption::Online != $request->step_1['attendingOption']
                    ) {
                        $book = $request->step_3['booked'];
                    }

                    if ($event->has_multiple_venues && $event->slug == 1226292026 && AttendingOption::Online != $request->step_1['attendingOption']) {
                        $book = array_combine($book, array_fill(0, count($book), $request->step_1['venue']));
                    }

                    if ($event->slug == 1226292026 && AttendingOption::Online != $request->step_1['attendingOption']) {
                        $book = $details->booked;
                    }

                    // Remove bookings without selected venue
                    // Bookings without venue means not booked
                    if ($event->has_multiple_venues && $event->slug != 1226292026) {
                        $book = array_filter($value['booked'], function ($venue) {
                            return !empty(trim((string)$venue));
                        });
                    }

                    if ($event->slug == 1226292026) {
                        $value['venue'] = $request->step_1['venue'];
                    }

                    $custom_fields_value = $this->getCustomFieldsValue($event->custom_fields, $value);

                    $category = 'Adult';

                    // ------------------------- custom blocks -------------------------------
                    $this->addCampingData($category, null, $value, $event, 'Guest');
                    // ------------------------- custom block ends here ----------------------

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
                        'category' => $category,
                        'attending_option' => $request->step_1['attendingOption'],
                        'medical_assistance_needed' => $details->specificMedicalAssistance,
                        'with_awta_card' => 'none',
                        'notes' => [],
                        'activities' => [],
                        'booking_activities' => [],
                        'custom_fields' => $custom_fields_value
                    ]);

                    $this->book($event, $registration, $book);

                    $registration = $this->updatePaymentStatus($registration->id, true);

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
                    'category' => 'Adult',
                    'attending_option' => AttendingOption::Online,
                    'with_awta_card' => 'none',
                    'notes' => [],
                    'activities' => [],
                    'booking_activities' => [],
                    'custom_fields' => $custom_fields_value
                ]);

                $registration = $this->updatePaymentStatus($registration->id, true);

                return $registration->uuid;
            }
        }
    }

    /**
     * Get all custome fields for an event registration
     *
     * @param $custom_fields
     * @param array $values
     */
    private function getCustomFieldsValue($custom_fields, $values)
    {
        if (!empty($custom_fields)) {
            $custom_fields_value = [];

            foreach ($custom_fields as $field) {
                $custom_fields_value[$field->name] = $values[$field->name] ?? $field->default; // or null if default is not set
            }

            return $custom_fields_value;
        } else {
            return null;
        }
    }

    /**
     * View edit page for registration
     *
     * @param Event $event
     * @param int $registration_id
     * @param Request $request
     */
    public function edit(Event $event, $registration_id, Request $request)
    {
        $event = Event::with('custom_fields')->find($event->id);

        return view('registration.edit', [
            'registration' => Registration::with('lookup')->where('id', $registration_id)->first(),
            'event' => $event,
            'search' => $request->search ?? ''
        ]);
    }

    /**
     * Delete registration
     *
     * @param Event $event
     * @param Registration $registration
     */
    public function destroy(Event $event, Registration $registration)
    {
        $registration->bookings()->delete();

        $registration->payments()->delete();

        $registration->attendances()->delete();

        return $registration->delete();
    }

    /**
     * Update registration
     *
     * @param Event $event
     * @param Registration $registration
     * @param Request $request
     */
    public function update(Event $event, Registration $registration, Request $request)
    {
        if (isset($request->avail_new_lamp_id)) { // save answer for newly registered members
            $registration->lookup()->update([
                'lamp_id' => $registration->uuid,
                'avail_new_lamp_id' => $request->avail_new_lamp_id,
            ]);

            $registration->additional_data()->update([
                'has_viewed_ticket' => NOW(),
            ]);
        } elseif (isset($request->mark_as_viewed)) { // mark as viewed for guests
            $registration->additional_data()->updateOrCreate(
                ['registration_id' => $registration->id],
                ['has_viewed_ticket' => now()]
            );
        } else {
            $event = Event::with('custom_fields')->find($event->id);

            $custom_fields_value = $this->getCustomFieldsValue($event->custom_fields, $request->all());

            $registration->update([
                'email' => $request->email,
                'firstname' => $request->firstName,
                'lastname' => $request->lastName,
                'fullname' => $request->firstName . ' ' . $request->lastName,
                'facebook_name' => $request->facebookName,
                'local_church' => $request->localChurch,
                'cluster_group' => $request->clusterGroup,
                'country' => $request->country,
                'category' => $request->category,
                'attending_option' => $request->attendingOption,
                'with_awta_card' => $request->withAwtaCard,
                'can_book_rate' => $request->bookingRate,
                'can_book_days' => $request->canBookDays,
                'rate' => $request->rate,
                'rebooking_limit' => $request->rebookingLimit,
                'visitor_to_member' => $request->visitorToMember ? date('Y-m-d', strtotime($request->visitorToMember)) : NULL,
                'custom_fields' => $custom_fields_value
            ]);

            $lookup = LookUp::where('lamp_id',  $registration->uuid)->first();

            if ($lookup) {
                $lookup->update([
                    'email' => $request->email,
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'fullname' => $request->firstName . ' ' . $request->lastName,
                    'facebook_name' => $request->facebookName,
                    'local_church' => $request->localChurch,
                    'country' => $request->country,
                    'category' => $request->category,
                    'cluster_group' => $request->clusterGroup
                ]);
            }

            // if has booking
            Booking::where('registration_id', $registration->id)->update([
                'local_church' => $request->localChurch,
            ]);

            if ($registration->registration_type === 'Member') {
                $registration->lookup()->update([
                    'lamp_id' => $registration->uuid,
                    'avail_new_lamp_id' => $request->availNewLAMPID,
                ]);
            }
        }

        if ($request->notes) {
            $registration->updateStaffNotes($registration, $registration->notes, array($request->notes));
        }

        return $this->updatePaymentStatus($registration->id, false);
    }

    /**
     * Resend email notification
     *
     * @param Event $event
     * @param int $id
     */
    public function resend_mail(Event $event, $id)
    {
        $registration = Registration::find($id);

        $registration->updateActivities($registration, $registration->activities, array(
            'resent email notification'
        ));

        $this->notify($id);
    }

    /**
     * Validation if already registered
     *
     * @param Event $event
     * @param Request $request
     */
    public function validation(Event $event, Request $request)
    {
        $isBulk = $request->isBulk === 'true';
        $booked = [];
        if ($isBulk) {
            $errors = [];

            foreach ($request->data as $key => $value) {
                $value = json_decode($value);

                if (!$value->firstName) {
                    $errors[$key]['firstName'] = 'First name is required.';
                }

                if (!$value->lastName) {
                    $errors[$key]['lastName'] = 'Last name is required.';
                }

                if (!$value->facebookName) {
                    $errors[$key]['facebookName'] = 'Facebook name is required.';
                }

                if (
                    !$value->email &&
                    $value->attendingOption == AttendingOption::Online &&
                    $event->enable_zoom_registration
                ) {
                    $errors[$key]['email'] = 'Email is required for online attendee.';
                } elseif ($value->email) {
                    if (!filter_var($value->email, FILTER_VALIDATE_EMAIL)) {
                        $errors[$key]['email'] = 'Email address is invalid.';
                    }
                }

                if (!$value->clusterGroup) {
                    $errors[$key]['clusterGroup'] = 'Cluster group is required.';
                }

                if (!$value->localChurch) {
                    $errors[$key]['localChurch'] = 'Local church is required.';
                }

                if (!$value->country) {
                    $errors[$key]['country'] = 'Country is required.';
                }

                if ($event->has_multiple_venues) {
                    $allEmpty = true;

                    foreach ((array) $value->booked as $k => $venue) {
                        if (!empty(trim($venue))) {
                            $allEmpty = false; // at least one value found
                            break;
                        }
                    }

                    if ($allEmpty && 'Online' != $value->attendingOption && $event->with_booking && $event->slug != 1226292026) {
                        if ($event->slug == 1226292026) {
                            $errors[$key]['booked'] = 'Please select your preferred dates.';
                        } else {
                            $errors[$key]['booked'] = 'Please select a venue for your preferred dates.';
                        }
                    }
                } else {
                    if (count($value->booked) === 0 && 'Online' != $value->attendingOption && $event->with_booking) {
                        $errors[$key]['booked'] = 'Select preferred dates.';
                    }
                }

                // ------------------------- custom blocks -------------------------------
                $this->addCampingValidations($errors, $key, $value, $event);
                // ------------------------- custom blocks ends here ---------------------

                if (!array_key_exists($key, $errors)) {
                    $validation = $this->checkIfAlreadyRegistered($event->id, (object) [
                        'firstName' => $value->firstName,
                        'lastName' => $value->lastName,
                        'localChurch' => $value->localChurch
                    ]);

                    if ($validation && array_key_exists('error', $validation)) {
                        $errors[$key]['invalid'] = $validation['error'];
                    }
                }

                if ($event->has_multiple_venues && $event->slug != 1226292026) {
                    $multi_venue_booked = array_keys(array_filter((array) $value->booked, function ($venue) {
                        return !empty(trim($venue));
                    }));

                    $booked = array_unique(array_merge($booked, $multi_venue_booked));
                } else {
                    $booked = array_unique(array_merge($booked, $value->booked));
                }
            }

            // check all slot if available
            $booking_error = [];
            $availability = [];
            foreach ($booked as $slot_id) {
                $slot = Slots::where('id', $slot_id)->first();

                if ($slot->available <= 0) {
                    $booking_error[] = $slot_id;
                }

                $availability[] = $slot->available;
            }

            // loop on all reg if has error with slot availability
            foreach ($request->data as $key => $value) {
                $value = json_decode($value);

                $multi_venue_booked = array_keys(array_filter((array) $value->booked, function ($venue) {
                    return !empty(trim($venue));
                }));

                if (count(array_intersect($multi_venue_booked, $booking_error)) > 0) {
                    $errors[$key]['booked'] = 'Some dates are already taken. Please refresh the page and try again.';
                }
            }

            if (count($errors) > 0) {
                return response()->json(['errors' => $errors], 500);
            }
        } else {
            $validation = $this->checkIfAlreadyRegistered($event->id, $request);

            if (array_key_exists('error', $validation)) {
                return response()->json(['error' => $validation['error']], 500);
            }
        }
    }

    /**
     * Form for guests after registration is closed
     *
     * @return blade
     */
    public function new()
    {
        return view('registration.create', [
            'slots' => [
                'member' => Slots::where('registration_type', RegistrationType::Member)->get(),
                'guest' => Slots::where('registration_type', RegistrationType::Guest)->get()
            ]
        ]);
    }

    /**
     * Export all registration in a specific event - excel file
     *
     * @param Event $event
     */
    public function export(Event $event)
    {
        return Excel::download(new ExportRegistration($event), 'registrations_' . TIME() . '.csv');
    }

    /**
     * Custom data for camping
     *
     * @param string $category
     * @param boolean $with_awta_card
     * @param array $details
     * @param Event $event
     * @param string $registration_type
     */
    private function addCampingData(&$category, $with_awta_card, $details, $event, $registration_type)
    {
        if ($event->slug == 7382159075) {
            $category = $details['camper_category'];
        }
    }

    /**
     * Custom validation for camping registration
     *
     * @param array $errors
     * @param int $key
     * @param object $value
     * @param Event $event
     */
    private function addCampingValidations(&$errors, $key, $value, $event)
    {
        if ($event->slug == 7382159075) {
            if (!$value->birthday) {
                $errors[$key]['birthday'] = 'Birth date is required.';
            }

            if (!$value->camper_category) {
                $errors[$key]['camper_category'] = 'Camper category is required.';
            }

            if (!$value->holy_ghost_seeker) {
                $errors[$key]['holy_ghost_seeker'] = 'Select an answer.';
            }

            if (!$value->inviter_complete_name) {
                $errors[$key]['inviter_complete_name'] = 'Name of Inviter is required.';
            }

            if (!$value->transportation) {
                $errors[$key]['transportation'] = 'Select preferred mode of transportation.';
            }

            if (!$value->tshirt_size) {
                $errors[$key]['tshirt_size'] = 'Select t-shirt size.';
            }

            if (count($value->booked) < 2 && 'Online' != $value->attendingOption) {
                $errors[$key]['booked'] = 'Select at least two days.';
            }
        }
    }
}
