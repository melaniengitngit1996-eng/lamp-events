<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Event;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Event $event, Request $request) {
        // to do: set by event
        $activities = Activity::with('user')->orderBy('created_at', 'desc');

        return view('activities.index', [
            'activities' => ActivityResource::collection($activities->get())->all(),
            'event' => $event
        ]);
    }
}