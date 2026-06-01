<?php

namespace Tests\Feature\Controllers\PasswordReset\Reset;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;

final class ResetPasswordAccessTest extends ResetPasswordTestSetUp
{
    public function test_an_unauthenticated_user_should_be_able_to_request_the_reset_password(): void
    {
        $this->postJson($this->route(), $this->data())
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('actions.password_reset.success.reset')));
    }
}
