<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slots;
use App\Models\Event;
use App\Models\SlotLocalChurch;

class SlotsController extends Controller
{
    public function index(Event $event)
    {
        return Slots::where('event_id', $event->id)
            ->with('localChurchSlots')
            ->get();
    }

    public function store(Event $event, Request $request)
    {
        $slot = Slots::find($request->selected['id']);

        if (empty($slot)) {
            return response()->json(['error' => 'Slot not found.'], 422);
        }

        $activities = $slot->activities;

        if (empty($activities)) {
            $activities = array([
                'user' => auth()->user()->name ?? '',
                'message' => $request->notes,
                'timestamp' => date('M d, Y h:i A')
            ]);
        } else {
            array_unshift($activities, [
                'user' => auth()->user()->name ?? '',
                'message' => $request->notes,
                'timestamp' => date('M d, Y h:i A')
            ]);
        }

        $slot->update([
            'seat_count' => $slot->seat_count + $request->additional_count,
            'activities' => $activities
        ]);

        return $slot;
    }

    public function updateLocalChurchSlots(
        Event $event,
        Slots $slot,
        Request $request
    ) {
        if ($slot->event_id !== $event->id) {
            return response()->json([
                'error' => 'Slot does not belong to this event.'
            ], 422);
        }

        $localChurchSlots = $request->local_church_slots;

        if (!is_array($localChurchSlots)) {
            return response()->json([
                'error' => 'Invalid Local Church slots.'
            ], 422);
        }

        $total = collect($localChurchSlots)->sum(function ($church) {
            return (int) ($church['seat_count'] ?? 0);
        });

        if ($total > $slot->seat_count) {
            return response()->json([
                'error' => 'The allocated slots cannot exceed the total slots.'
            ], 422);
        }

        foreach ($localChurchSlots as $church) {

            $localChurchSlot = !empty($church['id'])
                ? SlotLocalChurch::find($church['id'])
                : null;

            if ($localChurchSlot) {

                if ($localChurchSlot->slot_id != $slot->id) {
                    return response()->json([
                        'error' => 'Invalid Local Church slot.'
                    ], 422);
                }

                if ((int) $church['seat_count'] < (int) $localChurchSlot->taken) {
                    return response()->json([
                        'error' => $localChurchSlot->local_church .
                            ' cannot have fewer slots than the number already taken.'
                    ], 422);
                }

                $localChurchSlot->update([
                    'seat_count' => (int) $church['seat_count']
                ]);
            } else {

                SlotLocalChurch::create([
                    'slot_id' => $slot->id,
                    'local_church' => $church['local_church'],
                    'seat_count' => (int) $church['seat_count'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Local Church slots updated successfully.'
        ]);
    }
}
