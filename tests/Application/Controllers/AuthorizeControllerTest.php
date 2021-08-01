<?php

namespace Tests\Application\Controllers;

use App\V1\Application\Models\User;
use App\V1\Infrastructure\GoogleCalendarService;
use Tests\TestCase;

class AuthorizeControllerTest extends TestCase
{
    public function testItCanAuthorizeWithGoogleAlreadyAuthenticated()
    {
        User::factory()->admin()->create();

        $response = $this->get('api/authorize');
        $this->assertEquals($response->getContent(), 'already authenticated');
    }

    public function testItCanAuthorizeWithGoogleNotAuthenticated()
    {
        User::factory()->adminNotAuthorized()->create();

        $redirect = $this->faker->url;
        $googleCalenderService = $this->mock(GoogleCalendarService::class);
        $googleCalenderService->shouldReceive('getAuthorizeUrl')->once()
            ->andReturn($redirect);

        $response = $this->get('api/authorize');
        $response->assertRedirect($redirect);
    }

    public function testRedirected()
    {
        User::factory()->adminNotAuthorized()->create();

        $calenderId = $this->faker->uuid;
        $code = $this->faker->uuid;
        $creds = [
            'access_token' => $this->faker->uuid,
            'refresh_token' => $this->faker->uuid
        ];
        $googleCalenderService = $this->mock(GoogleCalendarService::class);
        $googleCalenderService->shouldReceive('getCredentials')->once()
            ->with($code)
            ->andReturn($creds);

        $googleCalenderService->shouldReceive('getCalenderId')->once()
            ->with($creds['access_token'])
            ->andReturn($calenderId);

        $response = $this->get('api/redirected?code=' . $code);
        $response->assertOk();
        $response->assertSeeText('success');
        $this->assertDatabaseHas('users', [
            'refresh_token' => $creds['refresh_token'],
            'calendar_id' => $calenderId
        ]);
    }
}
