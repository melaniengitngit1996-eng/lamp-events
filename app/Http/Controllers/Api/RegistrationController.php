<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegistrationResource;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Event;

class RegistrationController extends Controller
{
    public function index(Event $event)
    {       
        $registrations = Registration::select(array(
                'id',
                'created_at',
                'uuid',
                'email',
                'firstname',
                'lastname',
                'facebook_name',
                'registration_type',
                'local_church',
                'cluster_group',
                'country',
                'category',
                'attending_option',
                'with_awta_card',
                'rate',
                'payment_status',
                'booking_status',
                'medical_assistance_needed',
                'visitor_to_member',
                'custom_fields'
            ))
            ->where('event_id', $event->id)
            ->withSum('payments', 'amount', 'old_uuid')
            ->with(['attendances.slot'])
            ->get();

        return collect($registrations)->map(function ($reg) use ($event) {
            return (new RegistrationResource($reg, $event))->toArray(request());
        });
    }
}
