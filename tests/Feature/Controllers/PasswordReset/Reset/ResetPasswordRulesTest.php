<?php

namespace Tests\Feature\Controllers\PasswordReset\Reset;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class ResetPasswordRulesTest extends ResetPasswordTestSetUp
{
    public function test_should_errors_when_the_required_fields_are_missing(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.code.0', __('validation.required', [
                        'attribute' => __('validation.attributes.code'),
                    ]))
                    ->where('errors.email.0', __('validation.required', [
                        'attribute' => 'email',
                    ]))
                    ->where('errors.password.0', __('validation.required', [
                        'attribute' => __('validation.attributes.password'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_code_field_does_not_a_6_digit_number(): void
    {
        $this->postJson($this->route(),
            $this->data(['code' => Str::random(5)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.code.0', __('validation.digits', [
                        'attribute' => __('validation.attributes.code'),
                        'digits' => 6,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_does_not_contain_a_valid_email_address(): void
    {
        $this->postJson($this->route(),
            $this->data(['email' => Str::random(10)]))
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

    public function test_should_return_an_error_when_the_password_field_is_less_than_8_characters(): void
    {

        $this->postJson($this->route(),
            $this->data(['password' => Str::random(7)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.password.0', __('validation.min.string', [
                        'attribute' => __('validation.attributes.password'),
                        'min' => 8,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_field_is_longer_than_255_characters(): void
    {
        $this->postJson($this->route(), $this->data([
            'password' => Str::random(256),
        ]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', __('flash_messages.errors'))
                    ->where('errors.password.0', __('validation.max.string', [
                        'attribute' => __('validation.attributes.password'),
                        'max' => 255,
                    ]))
            );
    }

}
