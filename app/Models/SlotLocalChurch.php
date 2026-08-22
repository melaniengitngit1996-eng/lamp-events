<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotLocalChurch extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_id',
        'local_church',
        'seat_count',
    ];

    protected $appends = [
        'available',
        'taken',
    ];

    public function slot()
    {
        return $this->belongsTo(Slots::class, 'slot_id');
    }

    public function getTakenAttribute()
    {
        return Booking::where('slot_id', $this->slot_id)
            ->where('local_church', $this->local_church)
            ->whereIn('status', [
                BookingStatus::Confirmed,
                BookingStatus::Pending,
            ])
            ->count();
    }

    public function getAvailableAttribute()
    {
        return max(0, $this->seat_count - $this->taken);
    }
}
