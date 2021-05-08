<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Booking App</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <script>
        this.Laravel = {
            csrfToken: '{{csrf_token()}}',
            auth: {
                user: '{{auth()->user() ? auth()->user()->type : ""}}'
            }
        }
    </script>
</head>