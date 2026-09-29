<?php

namespace Tests\Feature\Controllers\DeviceController\Register;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class RegisterDeviceRulesTest extends RegisterDeviceTestSetUp
{
    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->user);
    }

    public function test_should_return_many_errors_when_the_required_fields_is_null_values(): void
    {
        $this->postJson($this->route())
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.device_model_id.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.device_model_id'),
                    ]))
                    ->where('errors.access_key.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.access_key'),
                    ]))
                    ->where('errors.color.0', trans('validation.required', [
                        'attribute' => trans('validation.attributes.color'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_device_model_id_field_is_not_integer(): void
    {
        Sanctum::actingAs($this->user);

        $this->postJson(
            $this->route(),
            $this->data(['device_model_id' => Str::random(4)])
        )
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.device_model_id.0', trans('validation.integer', [
                        'attribute' => trans('validation.attributes.device_model_id'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_device_model_id_field_does_not_exists(): void
    {
        $this->postJson(
            $this->route(),
            $this->data(['device_model_id' => 0])
        )
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.device_model_id.0', trans('validation.exists', [
                        'attribute' => trans('validation.attributes.device_model_id'),
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_color_field_is_longer_than_255_characters(): void
    {
        $this->postJson(
            $this->route(),
            $this->data(['color' => Str::random(256)])
        )
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.color.0', trans('validation.max.string', [
                        'attribute' => trans('validation.attributes.color'),
                        'max' => 255,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_access_key_field_is_longer_than_44_characters(): void
    {
        $this->postJson(
            $this->route(),
            $this->data(['access_key' => $this->generateRandomNumber(45)])
        )
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.access_key.0', trans('validation.digits', [
                        'attribute' => trans('validation.attributes.access_key'),
                        'digits' => 44,
                    ]))
            );
    }

    public function test_should_return_an_error_when_the_access_key_field_value_already_in_use(): void
    {
        $this->postJson(
            $this->route(),
            $this->data(['access_key' => $this->device->invoice->access_key])
        )
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.access_key.0', trans('validation.unique', [
                        'attribute' => trans('validation.attributes.access_key'),
                    ]))
            );
    }
}
