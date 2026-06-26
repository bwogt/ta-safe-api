<?php

namespace Tests\Feature\Controllers\DeviceShareController\Get;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class GetDeviceByShareCodeRulesTest extends GetDeviceByShareCodeTestSetUp
{
    protected function setUp(): void
    {
        parent::SetUp();
        Sanctum::actingAs($this->user);
    }

    public function test_should_return_an_error_when_the_code_param_is_null(): void
    {
        $this->getJson($this->route())
            ->assertUnprocessable()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('message.type', FlashMessageType::ERROR)
                ->where('message.text', trans('flash_messages.errors'))
                ->where('errors.code.0', trans('validation.required', [
                    'attribute' => trans('validation.attributes.code'),
                ]))
            );
    }

    public function test_should_return_an_error_when_the_code_param_is_longer_than_8_digits(): void
    {
        $code = '1234567';

        $this->getJson($this->route($code))
            ->assertUnprocessable()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('message.type', FlashMessageType::ERROR)
                    ->where('message.text', trans('flash_messages.errors'))
                    ->where('errors.code.0', trans('validation.digits', [
                        'attribute' => trans('validation.attributes.code'),
                        'digits' => 8,
                    ]))
            );
    }

}
