<?php
return [
    'email' => env('ADMIN_USER_EMAIL'),
    'password' => env('ADMIN_PASSWORD'),
    'address' => [
        'co' => env('CO_ADDRESS'),
        'line' => env('ADDRESS'),
        'city' => env('CITY'),
        'zip' => env('ZIP'),
    ]
];
