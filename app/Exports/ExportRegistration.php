<?php

namespace App\Exports;

use App\Models\Registration;
use App\Models\ExportHistory;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ExportRegistrationResource;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportRegistration implements FromCollection, WithHeadings
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        ExportHistory::create([
            'event_id' => $this->event->id,
            'type' => 'registrations',
            'user_id' => Auth::user()->id
        ]);

        return ExportRegistrationResource::collection(
            Registration::select(array(
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
            ->where('event_id', $this->event->id)
            ->withSum('payments', 'amount', 'old_uuid')
            ->get()
        );
    }

    public function headings(): array
    {
        $event = Event::with('custom_fields')->find($this->event->id);

        $headers = array(
            'Date Registered',
            'ID',
            'Email',
            'First Name',
            'Last Name',
            'Facebook Name',
            'Registration Type',
            'Local Church',
            'Cluster Group',
            'Country',
            'Category',
            'Attending Option',
            'with AWTA Card number?',
            'will avail new LAMP ID?',
            'Booked Dates',
            'Booking Status',
            'Attended Dates',
            'Rate',
            'Payment Status',
            'Total Amount Paid',
            'Medical Assistance Needed',
            'Visitor to Member',
            'Old AWTA Card Number'
        );

        foreach ($event->custom_fields as $field) {
            $headers[] = $field->export_header;
        }

        return $headers;
    }
}
