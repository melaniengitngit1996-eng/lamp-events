<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Slots;
use App\Enums\RegistrationType;
use Illuminate\Http\Request;

class Registration2Controller extends Controller
{
    public function index($slug) {
        $event = Event::with('slots')->where('slug', $slug)->first();

        if (empty($event)) {
            abort(404);
        }

        if ($event->close_registration == true) {
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
}
