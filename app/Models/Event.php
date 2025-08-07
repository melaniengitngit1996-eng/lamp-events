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
        'has_multiple_venues' => 'boolean'
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
        $has_access = EventPermission::where('user_id', auth()->user()->id)->where('event_id', $this->id)->first();
        
        return !empty($has_access);
    }
}
