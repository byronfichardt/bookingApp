@component('mail::message')
# Pending Appointment.

Booking name, {{$user->name}}!

Appointment date: {{$booking->start_time}}

@component('mail::panel')
@component('mail::table')

| Description           | Price                      | Time (minutes)               |
| --------------------- |:--------------------------:| ----------------------------:|
@foreach($booking->products as $product)
| {{$product['name']}}  | {{$product->getPrice($booking->start_time)->price}}      | {{$product['minutes'] * $product['pivot']['quantity']}}      |
@endforeach
| Totals                | {{$totalPrice}}            | {{$totalTime}}               |

@endcomponent
@endcomponent

@component('mail::button', ['url' => $cancelUrl, 'color' => 'error'])
    Cancel
@endcomponent

@component('mail::button', ['url' => $approveUrl, 'color' => 'success'])
    Approve
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
