<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class SendController extends Controller
{
    public function __invoke(Request $request)
    {
        $endpoint = Config::get('services.sendmessage.endpoint');
        $recipients = $request->get('to');
        foreach ($recipients as $recipient_id) {
            $data = [
                'to' => $recipient_id,
                'message' => $request->get('message'),
            ];
            Http::post($endpoint, $data)->throw();
        }
    }
}
