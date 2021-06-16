<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
            <div class="text-xl font-medium text-gray-500 border-gray-400 tracking-wider">
                Booking Canceled.
            </div>
            <div class="ml-2 text-lg text-gray-500 tracking-wider">
                We hope to see you soon :)
            </div>
        </div>

        <div class="flex items-center pt-8 sm:justify-center sm:pt-0">
            <div class="text-xl font-medium text-gray-500 border-gray-400 tracking-wider">
                Want to book again?
            </div>
        </div>
        <div class="flex items-center pt-8 sm:justify-center sm:pt-0">
            <div class="text-xl font-medium text-blue-500 border-gray-400 tracking-wider">
                <a href="{{ url('/#book')}}">Click here!</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
