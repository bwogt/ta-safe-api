<?php

namespace Tests\Feature\Controllers\PasswordReset\Start;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class StartPasswordResetRulesTest extends StartPasswordResetTestSetUp
{
    public function test_should_return_an_error_when_the_email_field_are_missing(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.email.0', __('validation.required', [
                        'attribute' => 'email',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_does_not_contain_a_valid_email_address(): void
    {
        $this->postJson($this->route(), [
            'email' => Str::random(10),
        ])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.email.0', __('validation.email', [
                        'attribute' => 'email',
                    ]))
            );
    }
}
