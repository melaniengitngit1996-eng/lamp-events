<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'registration_uuid',
        'slot_id',
        'local_church',
        'status'
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
        $attendance = Attendance::where('registration_uuid', $this->registration_uuid)->where('slot_id', $this->slot_id)->first();

        return $attendance ? $attendance->notes : 'Pending';
    }

    /**
     * Get the attendance status of booking
     */
    public function getIsHappeningAttribute()
    {
        return $this->slot_id == env('SLOT_ID_TODAY_GUEST') || $this->slot_id == env('SLOT_ID_TODAY_MEMBER');
    }

    /**
     * Get the delegate that owns the payment.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_uuid', 'uuid');
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
        return Attendance::where('registration_uuid', $this->registration_uuid)->where('slot_id', $this->slot_id)->first();
    }
}
