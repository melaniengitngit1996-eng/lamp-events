<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function show(Event $event, $uuid)
    {
        $registration = Registration::with('bookings', 'bookings.slot')->where('event_id', $event->id)->where('uuid', $uuid)->first();

        $registration->booked_dates = array_map(function ($dates) {
            return $dates['slot']['event_date'];
        }, $registration->bookings->toArray());

        return view('ticket.show', [
            'registration' => $registration,
            'event' => $event
        ]);
    }

    public function edit(Event $event, $id)
    {
        $registration = Registration::with('bookings', 'bookings.slot', 'event')->find($id);

        $registration->booked_dates = array_map(function ($dates) {
            return $dates['slot']['event_date'];
        }, $registration->bookings->toArray());

        return view('ticket.edit', [
            'registration' => $registration,
            'event' => $event
        ]);
    }
}
