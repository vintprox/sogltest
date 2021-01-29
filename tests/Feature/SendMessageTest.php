<?php

namespace Tests\Feature;

use App\Http\Controllers\SendController;
use Illuminate\Support\Facades\Http;
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
}
