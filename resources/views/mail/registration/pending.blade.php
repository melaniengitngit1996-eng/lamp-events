@component('mail::message')
<center>
<label style="font-size: 20px; color: orange; font-weight: 600;">@if($event_slug == 7382159074 || $event_slug == 7382159075) Registration On-Hold! @else Booking On-Hold! @endif<label>
</center>
<br />
<br />
 
<b>Hi {{ $name }},</b>

@if($event_slug == 7382159074)
Please settle the full balance on or before the due date indicated below. Otherwise, your registration will automatically be cancelled.
@else
Please settle your balance or atleast pay half to confirm your booking on or before the due date indicated below. Otherwise, your booking will automatically be cancelled.<br />
@endif

@component('mail::panel')
<b>Balance:</b> Php {{ $balance }}<br />
@if($event_slug == 7382159074)
<b>Payment Due Date:</b> {{ $payment_due_date }}<br />
@else
<b>Minimum Payment Due:</b> Php {{ $minimum_due }}<br />
<b>Minimum Payment Due Date:</b> {{ $minimum_payment_due_date }}<br />
<b>Full Payment Due Date:</b> {{ $payment_due_date }}<br />
@endif
@endcomponent

@if($event_slug == 7382159075)
To settle it, please reach out to camp registration team.
@else
To settle it, please reach out to your local Registrars.
@endif

We hope to see you there!
 
@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent