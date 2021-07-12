<?php

namespace App\Domain;

use App\Application\Models\User;
use App\Infrastructure\GoogleCalendarService;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

class CalendarEventInserter
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function addEvent(string $summary, string $description, string $start, string $end): Event
    {
        $user = User::where('type', 'admin')->first();
        $creds = $this->calendarService->exchangeToken($user->refresh_token);

        $calendarClient = $this->calendarService->authorizeClient($creds['access_token']);

        $startTime = new EventDateTime();
        $startTime->setDateTime($start);

        $endTime = new EventDateTime();
        $endTime->setDateTime($end);

        $event = new Event();
        $event->setSummary($summary);
        $event->setDescription($description);
        $event->setStart($startTime);
        $event->setEnd($endTime);

        return $calendarClient->events->insert(
            $user->calendar_id,
            $event
        );
    }
}
