@component('mail::message')
# Pending Appointment.

Booking name, {{$user->name}}!

Appointment date: {{$booking->start_time}}

@component('mail::panel')
@component('mail::table')

| Description           | Price                      | Time (minutes)               |
| --------------------- |:--------------------------:| ----------------------------:|
@foreach($booking->products->toArray() as $product)
| {{$product['name']}}  | {{$product['price']}}      | {{$product['minutes'] * $product['pivot']['quantity']}}      |
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
