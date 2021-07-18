<?php

namespace App\V1\Infrastructure;

use Google_Service_Calendar;

class GoogleCalendarService
{
    private GoogleCalenderClient $calendarClient;

    public function __construct(GoogleCalenderClient $calendarClient)
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

    public function getAccessToken($authCode)
    {
        return $this->getCredentials($authCode)['access_token'];
    }

    public function getCalenderId($accessToken): ?int
    {
        $calendarClient = $this->authorizeClient($accessToken);

        $calenders = $calendarClient->calendarList->listCalendarList();
        foreach($calenders->getItems() as $calendarListEntry) {
            if($calendarListEntry->getSummary() == 'Bookings') {
                $calendarId = $calendarListEntry->getId();
                break;
            }
            $calendarId = null;
        }

        return $calendarId;
    }

    public function getRefreshToken($authCode)
    {
        return $this->getCredentials($authCode)['refresh_token'];
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
