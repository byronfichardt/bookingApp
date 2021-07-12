<?php

namespace App\Infrastructure;

use Google_Service_Calendar;

class GoogleCalendarService
{
    private GoogleCalanderClient $calendarClient;

    public function __construct(GoogleCalanderClient $calendarClient)
    {
        $this->calendarClient = $calendarClient;
    }

    protected function getClient(): Google_Service_Calendar
    {
        return new Google_Service_Calendar($this->calendarClient->getGoogleClient());
    }

    public function getCredentials($authCode): array
    {
        return $this->calendarClient
            ->getGoogleClient()
            ->fetchAccessTokenWithAuthCode($authCode);
    }

    public function getAuthorizeUrl(): string
    {
        return $this->calendarClient->getGoogleClient()->createAuthUrl();
    }

    public function exchangeToken($refreshToken): array
    {
        return $this->calendarClient
            ->getGoogleClient()
            ->fetchAccessTokenWithRefreshToken($refreshToken);
    }

    public function authorizeClient($accessToken): Google_Service_Calendar
    {
        $this->calendarClient
            ->getGoogleClient()
            ->setAccessToken($accessToken);

        return $this->getClient();
    }
}
