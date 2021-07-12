<?php


namespace App\Application\Controllers;


use App\Application\Models\User;
use App\Http\Controllers\Controller;
use App\Infrastructure\GoogleCalanderClient;
use App\Infrastructure\GoogleCalendarService;
use Illuminate\Http\Request;

class AuthorizeController extends Controller
{
    private GoogleCalendarService $client;

    public function __construct(GoogleCalendarService $client)
    {
        $this->client = $client;
    }

    public function authorizeWithGoogle()
    {
        $user = User::where('type', 'admin')->first();
        if($user->refresh_token) {
            return 'already authenticated';
        }
        $redirectToGoogle = $this->client->getAuthorizeUrl();

        return redirect($redirectToGoogle);
    }

    public function redirected(Request $request)
    {
        $authCode = $request->code;

        $credentials = $this->client->getCredentials($authCode);
        $accessToken = $credentials['access_token'];
        $calendarId = null;

        $calendarClient = $this->client->authorizeClient($accessToken);

        $calenders = $calendarClient->calendarList->listCalendarList();
        foreach($calenders->getItems() as $calendarListEntry) {
            if($calendarListEntry->getSummary() == 'Bookings') {
                $calendarId = $calendarListEntry->getId();
            }
        }

        $user = User::where('type', 'admin')->first();
        $user->refresh_token = $credentials['refresh_token'];
        $user->calendar_id = $calendarId;
        $user->save();

        return 'success';
    }
}
