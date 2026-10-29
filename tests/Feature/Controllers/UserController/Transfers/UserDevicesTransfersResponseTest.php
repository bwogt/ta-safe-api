<?php

namespace Tests\Feature\Controllers\UserController\Transfers;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class UserDevicesTransfersResponseTest extends UserDevicesTransfersTestSetUp
{
    public function test_should_return_transfer_data(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1, fn (AssertableJson $data) => $data
                        ->where('id', $this->transfer->id)
                        ->where('status', $this->transfer->status->value)
                        ->has('created_at')
                        ->etc()
                    )
                    ->etc()
            );
    }

    public function test_should_return_pagination_meta(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('meta')
                    ->where('meta.has_more_page', false)
                    ->where('meta.next_cursor', null)
                    ->etc()
            );
    }

    public function test_should_return_device_model_data(): void
    {
        Sanctum::actingAs($this->user);

        $device = $this->transfer->device;
        $model = $device->deviceModel;
        $brand = $model->brand;

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1, fn (AssertableJson $data) => $data
                        ->has('device_model')
                        ->where('device_model.id', $model->id)
                        ->where('device_model.name', $model->name)
                        ->where('device_model.ram', $model->ram)
                        ->where('device_model.storage', $model->storage)

                        ->has('device_model.brand')
                        ->where('device_model.brand.id', $brand->id)
                        ->where('device_model.brand.name', $brand->name)
                        ->etc()
                    )
                    ->etc()
            );
    }
}
