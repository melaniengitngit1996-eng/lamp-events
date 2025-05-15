<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Slots;
use App\Models\Registration;
use App\Enums\RegistrationType;
use Illuminate\Http\Request;

class Registration2Controller extends Controller
{
    /**
     * show dynamic registration form
     *
     * @param  String $slug
     */
    public function index($slug) {
        $event = Event::with('slots')->where('slug', $slug)->first();

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
    public function show($slug, Request $request)
    {
        $uuid = explode(',', $request->id);

        $registration = (array) Registration::with('bookings', 'bookings.slot', 'additional_data', 'lookup')->whereIn('uuid', $uuid)->get()->toArray();

        $registration = array_map(function ($data) {
            $data['booked_dates'] = array_map(function ($dates) {
                return $dates['slot']['event_date'];
            }, $data['bookings']);
            $data['avail_new_lamp_id'] = $data['lookup']['avail_new_lamp_id'] ?? NULL;
            $data['has_viewed_ticket'] = $data['additional_data']['has_viewed_ticket'] ?? NULL;

            return $data;
        }, $registration);

        $event = Event::with('slots')->where('slug', $slug)->first();

        if (empty($event)) {
            abort(404);
        }
        
        return view('registration.show', [
            'registration' => $registration,
            'event' => $event
        ]);
    }
}
