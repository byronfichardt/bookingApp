<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Booking App</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <link rel="preload" href="https://fonts.googleapis.com/icon?family=Material+Icons" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"></noscript>

    <link rel="preload" href="https://fonts.googleapis.com/css?family=Lato" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato"></noscript>

    <link rel="preload" href="https://fonts.googleapis.com/css?family=Montserrat" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat"></noscript>

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"></noscript>

    <script>
        this.Laravel = {
            csrfToken: '{{csrf_token()}}',
            auth: {
                user: '{{auth()->user() ? auth()->user()->type : ""}}'
            }
        }
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CQZTVKW8B7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-CQZTVKW8B7');
    </script>
</head>
