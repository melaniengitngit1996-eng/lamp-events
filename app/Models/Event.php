<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'with_booking' => 'boolean',
        'with_guest_booking_code' => 'boolean',
        'close_registration' => 'boolean',
        'display_disclosure_prompt' => 'boolean'
    ];

    public function slots()
    {
        return $this->hasMany(Slots::class, 'event_id', 'id');
    }
}
