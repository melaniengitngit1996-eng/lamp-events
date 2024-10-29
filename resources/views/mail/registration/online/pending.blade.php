@component('mail::message')
<center>
<label style="font-size: 20px; color: orange; font-weight: 600;">Pending Payment<label>
</center>
<br />
<br />
 
<b>Hi {{ $name }},</b>

Please settle your balance to confirm your registration.<br />

@component('mail::panel')
<b>Balance:</b> Php {{ $balance }}<br />
<b>Due Date:</b> {{ $payment_due_date }}<br />
@endcomponent

To settle it, please reach out to your local AWTA Registrars.

We hope to see you there!
@if ($registration->registration_type === 'Member')
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
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent