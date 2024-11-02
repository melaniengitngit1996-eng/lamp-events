<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slots extends Model
{
    use HasFactory;

    protected $appends = ['available', 'taken', 'percentage'];

    protected $casts = [
        'event_date'  => 'date:F d',
        'activities' => 'array'
    ];

    protected $fillable = [
        'seat_count',
        'registration_type',
        'event_date',
        'activities',
        'created_at',
        'updated_at'
    ];

    public function getAvailableAttribute()
    {
        $taken = Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::Pending
        ])->where('slot_id', $this->id)->count();

        return $this->seat_count - $taken;
    }

    public function getTakenAttribute()
    {
        $taken = Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::Pending
        ])->where('slot_id', $this->id)->count();

        return $taken;
    }

    public function getPercentageAttribute()
    {
        $taken = Booking::whereIn('status', [
            BookingStatus::Confirmed,
            BookingStatus::Pending
        ])->where('slot_id', $this->id)->count();
        
        return number_format(($taken / $this->seat_count) * 100, 2, '.', '');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'slot_id', 'id');
    }

    public function received_hg() {
        return $this->hasMany(Attendance::class, 'slot_id', 'id');
    }
}
