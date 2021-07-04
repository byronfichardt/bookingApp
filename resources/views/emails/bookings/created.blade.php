@component('mail::message')
# Appointment confirmed.

Hi, {{$name}}!

You appointment date: {{$bookingDate}}

Address:
{{$co_address}} <br/>
{{$address_line}}<br/>
{{$city}}<br/>
{{$zip}}


@component('mail::panel')
@component('mail::table')

| Description           | Price                      | Time (minutes)               |
| --------------------- |:--------------------------:| ----------------------------:|
@foreach($products as $product)
| {{$product['name']}}  | {{$product['price']}}      | {{$product['minutes']}}      |
@endforeach
| Totals                | {{$totalPrice}}            | {{$totalTime}}               |

@endcomponent
@endcomponent

## Please avoid cancelling or changes, up to 24 hours before the appointment date.

@component('mail::button', ['url' => $cancelUrl, 'color' => '#FF7F7F'])
Should you want to cancel :(
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
