<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'amount',
        'user_id',
        'date_paid'
    ];

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($model) {
            $registration = Registration::where('id', $model->registration_id)->first();

            self::logActivity('deleted a payment of ' . $registration->fullname, $registration->fullname);
        });
    }

    /**
     * Append custom columns to the model
     * 
     * @var array
     */
    protected $appends = ['user_name'];

    /**
     * Define the type column to every Item object instance
     * 
     * @return string
     */
    public function getUserNameAttribute()
    {
        return User::find($this->user_id)->name;
    }

    /**
     * Get the delegate that owns the payment.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Get the payment's date.
     *
     * @param  string  $value
     * @return string
     */
    public function getDatePaidAttribute($value)
    {
        return date_format(date_create($value), "M d, Y");
    }

    private static function logActivity($description, $delegate_name)
    {
        auth()->user()->activities()->create([
            'description' => $description,
            'delegate_name' => $delegate_name
        ]);
    }
}
