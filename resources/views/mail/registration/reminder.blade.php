@component('mail::message')
 
<b>Hi {{ $name }},</b>

We are reaching out to remind you about our highly anticipated Annual Worship and Thanksgiving Assembly, which is only 2 days away!

Here are the important details you need to mark on your calendar:
@component('mail::panel')
<b>Event:</b> Annual Worship and Thanksgiving Assembly<br />
<b>Event Date:</b> {{ $event_date }}<br />
<b>Event Timing:</b> 4pm<br />
<b>Venue:</b> Calamba Tent<br />
@endcomponent

We kindly request that you arrive a few minutes early to ensure a smooth start to the event. 

Please make sure to have your LAMP ID/guest ticket ready, either in printed or digital format, for a smooth check-in process on the day of the event. Additionally, we kindly ask you to familiarize yourself with the event house rules and go through the provided schedule to prepare for the assembly.

@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent

If you have any questions or require further information, please feel free to reach out to our local Registrars. They will be more than happy to assist you.

We can't wait to worship and give thanks together. See you there!

BE BLESSED PHYSICALLY, MATERIALLY, & SPIRITUALLY.
 
With warmest regards,<br>
{{ config('app.name') }}
@endcomponent