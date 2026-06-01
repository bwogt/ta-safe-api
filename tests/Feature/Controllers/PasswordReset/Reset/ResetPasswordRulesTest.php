<?php

namespace Tests\Feature\Controllers\PasswordReset\Reset;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;

final class ResetPasswordRulesTest extends ResetPasswordTestSetUp
{
    public function test_should_errors_when_the_required_fields_are_not_filled(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.code.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.code'),
                    ]))
                    ->where('errors.email.0', trans('validation.required', [
                        'attribute' => 'email',
                    ]))
                    ->where('errors.password.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.password'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_code_field_does_not_a_6_digit_number(): void
    {
        $this->postJson($this->route(),
            $this->data(['code' => Str::random(5)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.code.0', trans('validation.digits', [
                        'attribute' => trans('validation.attributes.code'),
                        'digits' => 6,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_email_field_does_not_have_a_valid_email(): void
    {
        $this->postJson($this->route(),
            $this->data(['email' => Str::random(10)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.email.0', trans('validation.email', [
                        'attribute' => 'email',
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_field_value_is_less_than_8_characters_longs(): void
    {

        $this->postJson($this->route(),
            $this->data(['password' => Str::random(7)]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.password.0', trans('validation.min.string', [
                        'attribute' => trans('validation.attributes.password'),
                        'min' => 8,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_password_field_value_is_longer_than_255_characters(): void
    {
        $this->postJson($this->route(), $this->data([
            'password' => Str::random(256),
        ]))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.password.0', trans('validation.max.string', [
                        'attribute' => trans('validation.attributes.password'),
                        'max' => 255,
                    ]))
            );
    }

}
