@component('mail::message')
 
<b>Hi {{ $name }},</b>

Just a quick reminder that the {{ $event_name }} is happening soon!

Here are the important details you need to mark on your calendar:
@component('mail::panel')
<b>Event:</b> {{ $event_name }}<br />
<b>Event Date:</b> {{ $event_date }}<br />
<b>Event Timing:</b> {{ $event_timing }}<br />
<b>Venue:</b> {{ $venue }}<br />
@endcomponent

We kindly ask that you arrive early for a smooth start and have your LAMP ID or guest ticket ready (printed or digital) for check-in. Please review the house rules and event schedule in advance.

@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent

If you have questions, feel free to contact your local Registrar—they’ll be happy to assist you.

We can't wait to worship and give thanks together. See you there!

BE BLESSED PHYSICALLY, MATERIALLY, & SPIRITUALLY.
 
With warmest regards,<br>
{{ config('app.name') }}
@endcomponent