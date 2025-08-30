<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {
        return view('events.index', [
            'events' => Event::all()
        ]);
    }

    public function all() {
        return Event::all();
    }
}
