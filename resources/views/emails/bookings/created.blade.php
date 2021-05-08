@component('mail::message')
# Your booking has been created

Thank you!!

Please read our terms & conditions <a href="">here.</a>

@component('mail::button', ['url' => $cancelUrl, 'color' => '#00897b'])
Should you want to cancel :( 
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent