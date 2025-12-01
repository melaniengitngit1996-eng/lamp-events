@component('mail::message')
 
<b>Hi {{ $name }},</b>

We sincerely apologize for any inconvenience, but we are <b>unable to confirm your slot at Calamba Tent
for the {{ $event_name }} at this time</b> due to an unsettled balance.

To ensure that you can still participate, we have <b>rebooked your slot to your nearest Satellite venue</b>.

Here’s your updated ticket:
@component('mail::button', ['url' => $url])
<center>View Ticket</center>
@endcomponent

For any questions or assistance, please don’t hesitate to reach your local registrars.

Again, we apologize for the inconvenience and thank you for your understanding. We look forward to seeing you at the Satellite venue!
 
Blessings,<br>
{{ config('app.name') }}
@endcomponent