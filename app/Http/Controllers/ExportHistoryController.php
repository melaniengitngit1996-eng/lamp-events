<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExportHistory;
use App\Models\Event;

class ExportHistoryController extends Controller
{
    public function index(Event $event, Request $request) {
        $history = ExportHistory::where('event_id', $event->id);

        if ($request->type) {
            $history = $history->where('type', $request->type);
        }

        return $history->get();
    }
}
