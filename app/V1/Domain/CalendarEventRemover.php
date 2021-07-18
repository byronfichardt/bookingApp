<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Infrastructure\GoogleCalendarService;

class CalendarEventRemover
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function remove(Booking $booking): void
    {
        $adminUser = User::admin();
        $creds = $this->calendarService->exchangeToken($adminUser->refresh_token);
        $calendarClient = $this->calendarService->authorizeClient($creds['access_token']);

        $calendarClient->events->delete(
            $adminUser->calendar_id,
            $booking->event_id
        );
    }
}
