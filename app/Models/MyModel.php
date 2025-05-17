<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    use HasFactory;

    protected $trackable = [
        'uuid',
        'email',
        'firstname',
        'lastname',
        'facebook_name',
        'registration_type',
        'local_church',
        'country',
        'category',
        'rate',
        'attending_option',
        'can_book_rate',
        'can_book_days',
        'cluster_group',
        'visitor_to_member',
        'avail_new_lamp_id'
    ];

    function getTrackable()
    {
        return $this->trackable;
    }

    function updateStaffNotes(Registration $registration, $details, array $messages)
    {
        $notes = $details;

        foreach ($messages as $message) {
            array_unshift($notes, [
                'user' => auth()->user()->name ?? '',
                'message' => $message,
                'timestamp' => date('M d, Y h:i A')
            ]);
        }

        $registration = $registration->update([
            'notes' => $notes
        ]);

        return $registration;
    }

    function updateActivities(Registration $registration, $details, array $messages)
    {
        $activities = $details;

        foreach ($messages as $message) {
            array_unshift($activities, [
                'user' => auth()->user()->name ?? '',
                'message' => $message,
                'timestamp' => date('M d, Y h:i A')
            ]);
        }

        $registration = $registration->update([
            'activities' => $activities
        ]);

        return $registration;
    }

    function updateBookingActivities(Registration $registration, $details, array $messages)
    {
        $booking_activities = $details;

        foreach ($messages as $message) {
            array_unshift($booking_activities, [
                'user' => auth()->user()->name ?? '',
                'message' => $message,
                'timestamp' => date('M d, Y h:i A')
            ]);
        }

        $registration = $registration->update([
            'booking_activities' => $booking_activities
        ]);

        return $registration;
    }
}
