<?php

namespace Tests\Feature;

use App\Http\Controllers\SendController;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class SendMessageTest extends TestCase
{
    /**
     * Test whether request to an endpoint is to be performed within controller's action.
     */
    public function testEndpointIntegrity()
    {
        $endpoint = Config::get('services.sendmessage.endpoint');
        $data = [
            'to' => [1],
            'message' => '',
        ];

        Http::fake([$endpoint => Http::response()]);
        $this->post(action(SendController::class), $data);
        Http::assertSent(function (Request $request) use ($endpoint) {
            return $request->url() == $endpoint;
        });
    }

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

    /**
     * Test whether message can be sent to multiple recipients.
     */
    public function testMultipleRecipients()
    {
        $recipients = [1, 2, 3, 4, 5];
        $data = [
            'to' => $recipients,
            'message' => '',
        ];
        $sent_count = 0;

        Http::fake();
        $this->post(action(SendController::class), $data);
        Http::assertSent(function (Request $request) use ($recipients, &$sent_count) {
            return in_array($request['to'], $recipients) && $sent_count++;
        });
        $this->assertEquals(count($recipients), $sent_count);
    }
}
