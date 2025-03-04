<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'local_church',
        'created_at',
        'updated_at'
    ];
}
