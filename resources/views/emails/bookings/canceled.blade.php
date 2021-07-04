@component('mail::message')
# We are sad to see you go :(

@component('mail::button', ['url' => config('app.url'), 'color' => 'success'])
    Should you want to book again.
@endcomponent

We hope to see you soon!!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
