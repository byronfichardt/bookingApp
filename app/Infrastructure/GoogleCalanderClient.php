<?php

namespace App\Infrastructure;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Config\Repository;

class GoogleCalanderClient
{
    private Google_Client $client;

    public function __construct(Google_Client $client, Repository $config)
    {
        $client->setApplicationName('Impulse Nails');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setClientId($config->get('google.client_id'));
        $client->setClientSecret($config->get('google.client_secret'));
        $client->setRedirectUri($config->get('app.url') . '/api/redirected');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $this->client = $client;
    }

    public function getGoogleClient(): Google_Client
    {
        return $this->client;
    }
}
