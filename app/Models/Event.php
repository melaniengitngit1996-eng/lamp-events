<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public function slots()
    {
        return $this->hasMany(Slots::class, 'event_id', 'id');
    }
}
