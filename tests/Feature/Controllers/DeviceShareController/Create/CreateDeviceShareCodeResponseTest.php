<?php

namespace Tests\Feature\Controllers\DeviceShareController\Create;

use App\Enums\FlashMessage\FlashMessageType;
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
                    ->where('message.type', FlashMessageType::SUCCESS)
                    ->where('message.text', __('actions.device_share.success.create'))
                    ->has('code')
            );
    }
}
