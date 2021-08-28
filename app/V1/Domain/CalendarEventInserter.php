<?php

namespace App\V1\Domain;

use App\V1\Application\Models\Booking;
use App\V1\Application\Models\User;
use App\V1\Infrastructure\GoogleCalendarService;
use Carbon\Carbon;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;

class CalendarEventInserter
{
    private GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function addEvent(string $summary, array $products, Booking $booking): Event
    {
        $description = "Name: $booking->note. Products: " . implode(', ', $products);

        $startTime = $this->setStartEventDateTime($booking->start_time);
        $endTime = $this->setEndEventDateTime($booking->end_time);

        $event = $this->createEvent($summary, $description, $startTime, $endTime);

        $user = User::admin();
        $creds = $this->calendarService->exchangeToken($user->refresh_token);

        $user->refresh_token = $creds['refresh_token'];
        $user->save();

        $calendarClient = $this->calendarService->authorizeClient($creds['access_token']);

        return $calendarClient->events->insert(
            $user->calendar_id,
            $event
        );
    }

    public function setStartEventDateTime(string $date): EventDateTime
    {
        $date = Carbon::parse($date, 'Europe/Copenhagen')->toIso8601String();

        $startTime = new EventDateTime();
        $startTime->setDateTime($date);

        return $startTime;
    }

    public function setEndEventDateTime(string $date): EventDateTime
    {
        $date = Carbon::parse($date, 'Europe/Copenhagen')->toIso8601String();

        $endTime = new EventDateTime();
        $endTime->setDateTime($date);

        return $endTime;
    }

    /**
     * @param string $summary
     * @param string $description
     * @param EventDateTime $startTime
     * @param EventDateTime $endTime
     * @return Event
     */
    public function createEvent(string $summary, string $description, EventDateTime $startTime, EventDateTime $endTime): Event
    {
        $event = new Event();
        $event->setSummary($summary);
        $event->setDescription($description);
        $event->setStart($startTime);
        $event->setEnd($endTime);
        return $event;
    }
}
