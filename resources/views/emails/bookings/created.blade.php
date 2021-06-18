@component('mail::message')
# Your appointment has been confirmed.

Hi, {{$name}}!

You appointment date: {{$bookingDate}}

Address:
{{$co_address}} <br/>
{{$address_line}}<br/>
{{$city}}<br/>
{{$zip}}

## Please avoid cancelling or changes, up to 24 hours before the appointment date.

@component('mail::button', ['url' => $cancelUrl, 'color' => '#00897b'])
Should you want to cancel :(
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
