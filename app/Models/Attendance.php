<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'registration_uuid',
        'slot_id',
        'local_church',
        'registration_type',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'date:M d, Y H:i A',
    ];

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
}
