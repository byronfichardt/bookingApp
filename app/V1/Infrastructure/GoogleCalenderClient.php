<?php

namespace App\V1\Infrastructure;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Config\Repository;

class GoogleCalenderClient
{
    private Google_Client $client;

    public function __construct(Google_Client $client, Repository $config)
    {
        $creds = $config->get('google.account');

        $client->setApplicationName('Impulse Nails');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig($creds);
        $client->setRedirectUri($config->get('app.url') . '/api/redirected');
        $this->client = $client;
    }

    public function getGoogleClient(): Google_Client
    {
        return $this->client;
    }
}
