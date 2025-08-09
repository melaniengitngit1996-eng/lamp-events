<?php

namespace App\Http\Controllers;

use App\Models\AvailableUuid;
use App\Models\Rates;
use App\Models\Slots;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function show(Event $event) {
        return view('config.show', [
            'rates' => Rates::where('event_id', $event->id)->orderBy('registration_type', 'asc')->orderBy('category', 'asc')->get(),
            'availables' => AvailableUuid::all(),
            'slots' => Slots::where('event_id', $event->id)->get(),
            'event' => $event
        ]);
    }
}
