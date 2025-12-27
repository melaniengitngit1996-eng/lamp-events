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

        $data = $registration;

        $data['email'] = ($registration->email === null || $registration->email === '') ? 'no email' : $registration->email;

        return $data;
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

        $received = ReceivedHG::where('registration_uuid', $uuid)->first();

        if ($received) {
            return response()->json(['error' => 'This delegate has record already.'], 422);
        }

        $slot = Slots::where('event_id', $event->id)
            ->where('event_date', $request->date_received)
            ->where('registration_type', $registration->registration_type)
            ->first();

        if (!$slot) {
            return response()->json(['error' => 'Event not found.'], 422);
        }

        if (!$request->date_received) {
            return response()->json(['error' => 'Please select the date received.'], 422);
        }

        if (!$request->notes || $request->notes == '') {
            return response()->json(['error' => 'Please add notes.'], 422);
        }

        $hg = ReceivedHG::create([
            'event_id' => $event->id,
            'slot_id' => $slot->id,
            'registration_uuid' => $registration->uuid,
            'date_received' => $request->date_received,
            'local_church' => $registration->local_church,
            'registration_type' => $registration->registration_type,
            'notes' => $request->notes,
            'registration_id' => $registration->id
        ]);

        $date = date_create($request->date_received);

        $registration->update([
            'is_received_hg' => date_format($date, 'Y-m-d')
        ]);

        return response()->json([
            'success' => 'Successfully Recorded!',
            'data' => $hg
        ], 200);
    }

    public function export(Event $event) {
        return Excel::download(new ExportReceivedHG($event), 'received_hg_' . TIME() . '.csv');
    }

    public function destroy($id) {
        $record = ReceivedHG::find($id);

        $record->delete();
    }
}
