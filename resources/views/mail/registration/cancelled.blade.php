@component('mail::message')
<center>
<label style="font-size: 20px; color: red; font-weight: 600;">Booking Cancelled!<label>
</center>
<br />
<br />
 
<b>Hi {{ $name }},</b>

We're sorry to inform you that your booking for {{ $booked_dates }} has been cancelled, as the required amount was not settled within the 7-day payment window.<br />

@if ($with_booking)
    Please rebook and provide the following details:
@else
    For questions or rebooking, please contact your local coordinator with these details:
@endif

@component('mail::panel')
    <b>Last Name:</b> {{ $registration->lastname }}<br />
    <b>Local Church:</b> {{ $registration->local_church }}<br />
    <b>LAMP ID:</b> {{ $registration->uuid }}<br />
@endcomponent

@if ($with_booking)
    @component('mail::button', ['url' => $url])
    <center>Manage Booking</center>
    @endcomponent
@else
    Thank you for your understanding.
@endif
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent