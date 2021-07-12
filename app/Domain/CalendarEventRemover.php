<?php

namespace App\Domain;

use App\Application\Models\Booking;
use App\Application\Models\User;
use App\Infrastructure\GoogleCalendarService;

class CalendarEventRemover
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function remove(Booking $booking)
    {
        $adminUser = User::where('type', 'admin')->first();
        $creds = $this->calendarService->exchangeToken($adminUser->refresh_token);
        $calendarClient = $this->calendarService->authorizeClient($creds['access_token']);

        $calendarClient->events->delete(
            $adminUser->calendar_id,
            $booking->event_id
        );
    }
}
