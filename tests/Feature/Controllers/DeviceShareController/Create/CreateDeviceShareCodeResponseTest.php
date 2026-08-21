<?php

namespace Tests\Feature\Controllers\DeviceShareController\Create;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class CreateDeviceShareCodeResponseTest extends CreateDeviceShareCodeTestSetUp
{
    public function test_should_return_created_response_with_success_message_and_code(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson($this->route())
            ->assertCreated()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('code')
                    ->has('expires_at')
            );
    }
}
