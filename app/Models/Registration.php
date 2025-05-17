<?php

namespace App\Models;

use App\Enums\RegistrationType;
use App\Models\LookUp;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends MyModel
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'email',
        'firstname',
        'lastname',
        'fullname',
        'facebook_name',
        'registration_type',
        'local_church',
        'country',
        'category',
        'rate',
        'payment_status',
        'booking_status',
        'attending_option',
        'with_awta_card',
        'can_book_rate',
        'can_book_days',
        'rebooking_limit',
        'cluster_group',
        'visitor_to_member',
        'notes',
        'activities',
        'booking_activities',
        'medical_assistance_needed',
        'booked_date',
        'is_received_hg',
        'event_id'
    ];

    protected $casts = [
        'notes' => 'array',
        'activities' => 'array',
        'booking_activities' => 'array',
        'is_received_hg' => 'date:M d, Y',
        'event' => 'object'
    ];

    protected $appends = [
        "old_uuid"
    ];

    public function getOldUuidAttribute()
    {
        $old = LookUp::where('lamp_id', $this->uuid)->first();
        return $this->registration_type == 'Member' ?
            ($old ? $old->old_lamp_card_number : '--')
            : '--';
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $payment_config = Rates::where('event_id', $model->event_id)
                ->where('category', $model->category)
                ->where('attending_option', $model->attending_option)
                ->first();

            $model->rate = $payment_config->rate;
            $model->can_book_rate = $payment_config->can_book_rate;
        });

        self::updating(function ($model) {
            self::logActivity('updated the registration details of ' . $model->fullname, $model->fullname);
        });

        self::deleting(function ($model) {
            self::logActivity('deleted the registration details of ' . $model->fullname, $model->fullname);
        });

        self::updated(function ($model) {
            if (count($model->getFillableChanges()) > 0) {
                $model->updateActivities($model, $model->activities, array(
                    'updated ' . implode(', ', $model->getFillableChanges())
                ));
            }
        });
    }

    /**
     * Get the payments for the delegate.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the booking for the delegate.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('slot_id', 'asc');
    }

    /**
     * Get the booking for the delegate.
     */
    public function additional_data()
    {
        return $this->hasOne(RegistrationAdditionalData::class, 'registration_uuid', 'uuid');
    }

    public function available_bookings($id) {
        return $this->bookings()->where('id', $id);
    }

    /**
     * Get the event tagged
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }

    /**
     * Get the attendance for the delegate.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function lookup()
    {
        return $this->hasOne(LookUp::class, 'lamp_id', 'uuid');
    }

    private static function logActivity($description, $delegate_name)
    {
        if (auth()->user()) {
            auth()->user()->activities()->create([
                'description' => $description,
                'delegate_name' => $delegate_name
            ]);
        }
    }

    public function getFillableChanges(): array
    {
        $array = array_intersect_key($this->getChanges(), array_flip($this->getTrackable()));

        $array = array_keys($array);

        $array = array_map(function ($x) {
            return __(sprintf('columns.%s', $x));
        }, $array);

        return $array;
    }
}
