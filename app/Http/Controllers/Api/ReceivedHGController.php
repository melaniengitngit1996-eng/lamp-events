<?php

namespace App\Http\Controllers\Api;

use App\Models\ReceivedHG;
use App\Models\Registration;
use App\Models\Slots;
use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportReceivedHG;

class ReceivedHGController
{
    public function index(Event $event, Request $request) {
        $search = json_decode($request->search);

        $receivedHG = ReceivedHG::with('registration', 'slot')->where('event_id', $event->id);

        if ($search->local_church) {
            $receivedHG = $receivedHG->where('local_church', $search->local_church);
        }

        if ($search->keyword) {
            $receivedHG = $receivedHG->whereRelation('registration', 'fullname', 'LIKE', "%$search->keyword%")
                ->orWhereRelation('registration', 'uuid', 'LIKE', "%$search->keyword%");
        }

        $receivedHG = $receivedHG->paginate(10);

        return $receivedHG;
    }

    public function show(Event $event, $uuid, Request $request)
    {
        if (!$request->api_key) {
            return response()->json(['error' => 'API key is required.'], 403);
        }

        if ($request->api_key !== config('settings.api_key')) {
            return response()->json(['error' => 'API key is invalid.'], 403);
        }

        if (is_null($uuid)) {
            return response()->json(['error' => 'LAMP ID/Guest code is required.'], 422);
        }

        $registration = Registration::select('uuid', 'email', 'fullname', 'registration_type', 'local_church', 'cluster_group', 'country', 'attending_option')->where('uuid', $uuid)->where('event_id', $event->id)->first();

        if (!$registration) {
            return response()->json(['error' => 'Delegate not found.'], 422);
        }

        return $registration;
    }

    public function store(Event $event, $uuid, Request $request)
    {
        if (is_null($uuid)) {
            return response()->json(['error' => 'LAMP ID/Guest code is required.'], 422);
        }

        $registration = Registration::where('uuid', $uuid)->where('event_id', $event->id)->first();

        if (!$registration) {
            return response()->json(['error' => 'Delegate not found.'], 422);
        }

        $received = ReceivedHG::where('registration_uuid', $uuid)->where('event_id', $event->id)->first();

        if ($received) {
            return response()->json(['error' => 'This delegate has record already.'], 422);
        }

        if (!$registration) {
            return response()->json(['error' => 'Delegate not found.'], 422);
        }

        if (!$request->day) {
            return response()->json(['error' => 'Please select AWTA day.'], 422);
        }

        if (!$request->notes || $request->notes == '') {
            return response()->json(['error' => 'Please add notes.'], 422);
        }

        $slots = $event->slots;

        $groupedSlots = $slots->groupBy('description')->map(function ($items) {
            return $items->pluck('id')->all();
        });
        
        $slots = $groupedSlots[$request->day];

        if ($registration->registration_type === 'Member') {
            $slot_id = $slots[0];
        } else {
            $slot_id = $slots[1];
        }

        $hg = ReceivedHG::create([
            'event_id' => $event->id,
            'registration_uuid' => $registration->uuid,
            'slot_id' => $slot_id,
            'local_church' => $registration->local_church,
            'registration_type' => $registration->registration_type,
            'notes' => $request->notes
        ]);

        $slot = Slots::find($slot_id)->getOriginal('event_date');

        $registration->update([
            'is_received_hg' => date_format($slot, 'Y-m-d')
        ]);

        return response()->json([
            'success' => 'Successfully Recorded!',
            'data' => $hg
        ], 422);
    }

    public function export(Event $event) {
        return Excel::download(new ExportReceivedHG($event), 'received_hg_' . TIME() . '.csv');
    }

    public function destroy($id) {
        $record = ReceivedHG::find($id);

        $record->delete();
    }
}
