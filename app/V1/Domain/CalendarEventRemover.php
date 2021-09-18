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
        $calendarId = config('google.id');
        $calendarClient = $this->calendarService->getClient();

        $calendarClient->events->delete(
            $calendarId,
            $booking->event_id
        );
    }
}
