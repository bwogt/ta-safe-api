<?php

namespace Tests\Feature\Controllers\PasswordReset\Start;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;

final class StartPasswordResetAccessTest extends StartPasswordResetTestSetUp
{
    public function test_an_unauthenticated_user_should_be_able_to_request_the_forgot_password(): void
    {
        $this->postJson($this->route(), [
            'email' => $this->user->email,
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', __('actions.password_reset.success.start')));
    }
}
