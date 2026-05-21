@component('mail::message')
<center>
<label style="font-size: 20px; color: #6ea56e; font-weight: 600;">@if($event_slug == 7382159074 || $event_slug == 7382159075 || $event_slug == 7382159777) Registration Confirmed! @else Booking Confirmed! @endif<label>
</center>
<br />
<br />

<b>Hi {{ $name }},</b>

@if($event_slug == 7382159074 || $event_slug == 7382159075 || $event_slug == 7382159777)
Congratulations, your registration is already confirmed!
@else
Congratulations, your booking is already confirmed!
@endif

@component('mail::panel')
<b>Event:</b> {{ $event_name }} <br />
<b>Booked Dates:</b> {{ $booked_dates }}<br />
<b>Location:</b> {{ $venue }}  <a href="{{ $map }}">View Location</a> <br />
<b>Event Time:</b> {{ $event_timing }} <br />
<b>Theme:</b> {{ $theme }}<br />
@endcomponent

@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent

@if (!$enable_zoom_registration)
You may also join us via Zoom: <br />
<a href="{{ $zoom['link'] }}">{{ $zoom['link'] }}</a><br /><br />
Meeting ID: {{ $zoom['id'] }} <br />
Passcode: {{ $zoom['passcode'] }} <br />
@endif
<br /><br />

We will be sending a reminder before the event starts too!

@if ($fb_group_url)
For more updates, please join our facebook group: <a href="{{ $fb_group_url }}">{{ $fb_group_url }}</a>
@endif

See you there! 
@if ($enable_id_issuance)
@component('mail::subcopy')
<table>
    <tr>
        <td width="140">
            <img width="130" height="80" class="mx-2 mt-3 rounded shadow" src="https://lampawta.com/images/new_id.jpg">
        </td>
        <td style="word-break: normal !important; text-align: justify !important;">
            <small style="line-height: 0px; word-break: normal !important; text-align: justify !important;">Note: <i>A new LAMP ID Number is issued for you.</i> If you wish to replace your old AWTA card, an additional Php 35.00 will be required. Kindly reach out to your local Registrars for payment and issuance.</small>
        </td>
    </tr>
</table>
<br />
<br />
@endcomponent
@endif

Thanks,<br />
{{ config('app.name') }}
@endcomponent