<?php

namespace Tests\Feature\Auth\Password;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class ForgotPasswordRulesTest extends ForgotPasswordTestSetUp
{
    public function test_should_return_an_error_when_the_email_field_are_null_value(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.email.0', trans('validation.required', [
                        'attribute' => 'email',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_does_not_have_a_valid_email(): void
    {
        $this->postJson($this->route(), [
            'email' => Str::random(10),
        ])
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.email.0', trans('validation.email', [
                        'attribute' => 'email',
                    ]))
            );
    }
}
