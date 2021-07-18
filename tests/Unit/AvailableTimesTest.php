<?php

namespace Tests\Unit;

use App\Application\Models\BlockedDate;
use App\Application\Models\Booking;
use App\Domain\AvailableTimes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableTimesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_empty_if_1_booking_and_2_blocked()
    {
        $date = now()->setHour(13)->setMinute(0);
        Booking::factory()->create([
            'start_time' => $date
        ]);

        BlockedDate::factory()->create([
            'blocked_date' => $date->format('Y-m-d'),
            'times' => '9, 16'
        ]);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get($date->format('Y-m-d'));

        $this->assertEquals([], $times);
    }

    /** @test */
    public function it_returns_if_1_booking_and_1_blocked()
    {
        $date = now()->setHour(13)->setMinute(0);
        Booking::factory()->create([
            'start_time' => $date
        ]);

        BlockedDate::factory()->create([
            'blocked_date' => $date->format('Y-m-d'),
            'times' => '9'
        ]);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get($date->format('Y-m-d'));

        $this->assertEquals([16], $times);
    }

    /** @test */
    public function it_returns_if_2_blocked()
    {
        $date = now()->setHour(13)->setMinute(0);

        BlockedDate::factory()->create([
            'blocked_date' => $date->format('Y-m-d'),
            'times' => '9, 13'
        ]);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get($date->format('Y-m-d'));

        $this->assertEquals([16], $times);
    }

    /** @test */
    public function it_returns_empty_if_3_blocked()
    {
        $date = now()->setHour(13)->setMinute(0);

        BlockedDate::factory()->create([
            'blocked_date' => $date->format('Y-m-d'),
            'times' => '9, 13, 16'
        ]);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get($date->format('Y-m-d'));

        $this->assertEquals([], $times);
    }

    /** @test */
    public function it_returns_empty_if_3_Booked()
    {
        $date1 = now()->setHour(9)->setMinute(0);
        $date2 = now()->setHour(13)->setMinute(0);
        $date3 = now()->setHour(16)->setMinute(0);

        Booking::factory()->create([
            'start_time' => $date1
        ]);
        Booking::factory()->create([
            'start_time' => $date2
        ]);
        Booking::factory()->create([
            'start_time' => $date3
        ]);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get(now()->format('Y-m-d'));

        $this->assertEquals([], $times);
    }

    /** @test */
    public function it_returns_if_no_blocked_and_no_booked()
    {
        $date = now()->setHour(13)->setMinute(0);

        $availableTimes = new AvailableTimes();
        $times = $availableTimes->get($date->format('Y-m-d'));

        $this->assertEquals([9,13,16], $times);
    }
}
