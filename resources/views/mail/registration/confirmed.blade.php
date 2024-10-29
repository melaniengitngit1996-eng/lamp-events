@component('mail::message')
<center>
<label style="font-size: 20px; color: #6ea56e; font-weight: 600;">Booking Confirmed!<label>
</center>
<br />
<br />

<b>Hi {{ $name }},</b>

Congratulations, your booking is already confirmed!

@component('mail::panel')
<b>Booked Dates:</b> {{ $booked_dates }}<br />
<b>Location:</b> Calamba Tent, CMC Avenue, Crossing, Calamba City, Laguna  <a href="https://goo.gl/maps/avYUt5rPss9HDtDo7">View Location</a> <br />
<b>Event Time:</b> 4PM<br />
<b>Theme:</b> {{ $theme }}<br />
@endcomponent

@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent

You may also join us via Zoom: <br />
<a href="{{ $zoom['link'] }}">{{ $zoom['link'] }}</a><br /><br />
Meeting ID: {{ $zoom['id'] }} <br />
Passcode: {{ $zoom['passcode'] }} <br />
<br /><br />
We will be sending a reminder before the event starts too!

For more updates, please join our facebook group: <a href="{{ $fb_group_url }}">{{ $fb_group_url }}</a>

See you there! 
@component('mail::subcopy')
<table>
    <tr>
        <td width="140">
            <img width="130" height="80" class="mx-2 mt-3 rounded shadow" src="https://lampawta.com/images/new_id.jpg">
        </td>
        <td style="word-break: normal !important; text-align: justify !important;">
            <small style="line-height: 0px; word-break: normal !important; text-align: justify !important;">Note: <i>A new LAMP ID Number is issued for you.</i> If you wish to replace your old AWTA card, an additional Php 35.00 will be required. Kindly reach out to your local AWTA Registrars for payment and issuance.</small>
        </td>
    </tr>
</table>
<br />
<br />
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent