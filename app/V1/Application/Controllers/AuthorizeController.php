<?php

namespace App\V1\Application\Controllers;

use App\V1\Application\Models\User;
use App\Http\Controllers\Controller;
use App\V1\Infrastructure\GoogleCalendarService;
use Illuminate\Http\Request;

class AuthorizeController extends Controller
{
    private GoogleCalendarService $calenderService;

    public function __construct(GoogleCalendarService $calenderService)
    {
        $this->calenderService = $calenderService;
    }

    public function authorizeWithGoogle()
    {
        if(User::admin()->refresh_token) {
            return 'already authenticated';
        }
        $redirectToGoogle = $this->calenderService->getAuthorizeUrl();

        return redirect($redirectToGoogle);
    }

    public function redirected(Request $request): string
    {
        $authCode = $request->input('code');

        $credentials = $this->calenderService->getCredentials($authCode);
        $accessToken = $credentials['access_token'];

        $calendarId = $this->calenderService->getCalenderId($accessToken);

        $user = User::admin();
        $user->refresh_token = $credentials['refresh_token'];
        $user->calendar_id = $calendarId;
        $user->save();

        return 'success';
    }
}
