<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'event_id'
    ];

    protected $casts = [
        'created_at'  => 'date:M d, Y H:i:s A',
    ];

    /**
     * Append custom columns to the model
     * 
     * @var array
     */
    protected $appends = [
        'user_name'
    ];

    /**
     * Define the type column to every Item object instance
     * 
     * @return string
     */
    public function getUserNameAttribute()
    {
        return User::find($this->user_id)->name;
    }
}
