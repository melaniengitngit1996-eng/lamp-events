<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'with_booking' => 'boolean',
        'show_attending_option' => 'boolean',
        'with_guest_booking_code' => 'boolean',
        'close_registration' => 'boolean',
        'display_disclosure_prompt' => 'boolean',
        'enable_online_checkin' => 'boolean',
        'has_multiple_venues' => 'boolean',
        'enable_id_issuance' => 'boolean',
        'enable_zoom_registration' => 'boolean'
    ];

    protected $appends = [
        "has_access"
    ];

    public function slots()
    {
        return $this->hasMany(Slots::class, 'event_id', 'id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function custom_fields()
    {
        return $this->hasMany(EventRegistrationCustomField::class);
    }

    public function venues()
    {
        return $this->hasMany(EventVenue::class);
    }

    public function getHasAccessAttribute()
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        return EventPermission::where('user_id', $user->id)
            ->where('event_id', $this->id)
            ->exists();
        }
}
