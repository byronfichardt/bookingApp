@component('mail::message')
# Appointment Reminder.

Hi, {{$name}}!

This is a friendly reminder that your appointment with {{ config('app.name') }} is happening soon.
Your appointment date: {{$bookingDate}}

Address:
{{$co_address}} <br/>
{{$address_line}}<br/>
{{$city}}<br/>
{{$zip}}

## Please avoid cancelling or changes, up to 24 hours before the appointment date.

@component('mail::button', ['url' => $cancelUrl, 'color' => 'error'])
Should you want to cancel :(
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
