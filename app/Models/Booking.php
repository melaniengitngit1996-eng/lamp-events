<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'slot_id',
        'event_id',
        'local_church',
        'status',
        'venue'
    ];

    protected $appends = [
        "attendance_status",
        "is_happening",
        "attendance"
    ];

    /**
     * Get the attendance status of booking
     */
    public function getAttendanceStatusAttribute()
    {
        $attendance = Attendance::where('registration_id', $this->registration_id)->where('slot_id', $this->slot_id)->first();

        return $attendance ? $attendance->notes : 'Pending';
    }

    /**
     * Get the attendance status of booking
     */
    public function getIsHappeningAttribute()
    {
        $event_id = $this->event_id;

        $event = Cache::rememberForever('event::' . $this->event_id, function () use ($event_id) {
            return Event::find($event_id);
        });

        return $this->slot_id == $event->active_guest_slot_id || $this->slot_id == $event->active_member_slot_id;
    }

    /**
     * Get the delegate that owns the payment.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the delegate that owns the payment.
     */
    public function slot()
    {
        return $this->belongsTo(Slots::class, 'slot_id', 'id');
    }

    /**
     * Get the attendance.
     */
    public function getAttendanceAttribute()
    {
        return Attendance::where('registration_id', $this->registration_id)->where('slot_id', $this->slot_id)->first();
    }
}
