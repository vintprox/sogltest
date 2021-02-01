<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SendController extends Controller
{
    public function __invoke(Request $request)
    {
        $client = new Client();
        $endpoint = Config::get('services.sendmessage.endpoint');
        $recipients = $request->get('to');
        $message = $request->get('message');
        $promises = [];
        foreach ($recipients as $recipient_id) {
            $promises[] = $client->postAsync($endpoint, [
                'json' => [
                    'to' => $recipient_id,
                    'message' => $message,
                ],
            ]);
        }
        Promise\Utils::settle($promises)->wait();
    }
}
