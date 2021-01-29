<?php

namespace Tests\Feature;

use App\Http\Controllers\SendController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    /**
     * Test whether nothing is sent since there are no recipients.
     */
    public function testNothingSent()
    {
        $data = [
            'to' => [],
            'message' => '',
        ];

        Http::fake();
        $res = $this->post(action(SendController::class), $data);
        Http::assertNothingSent();
    }

    /**
     * Test whether requests to fake SendMessage service are valid.
     *
     * Integrity of both random recipient ID and message is challenged during this test.
     */
    public function testRandomMessage()
    {
        $to = rand();
        $message = Str::random();
        $data = [
            'to' => [$to],
            'message' => $message,
        ];

        Http::fake();
        $this->post(action(SendController::class), $data);
        Http::assertSent(function (Request $request) use ($to, $message) {
            return $request['to'] == $to && $request['message'] == $message;
        });
    }
}
