<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\AvailableUuid;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {
        return view('events.index', [
            'events' => Event::with('slots', 'custom_fields')->get(),
            'event_ids' => AvailableUuid::all()
        ]);
    }

    public function all() {
        return Event::all();
    }
}
