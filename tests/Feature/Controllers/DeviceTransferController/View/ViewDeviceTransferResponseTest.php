<?php

namespace Tests\Feature\Controllers\DeviceTransferController\View;

use App\Utils\Masks;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class ViewDeviceTransferResponseTest extends ViewDeviceTransferTestSetUp
{
    public function test_response_should_contain_transfer_attributes(): void
    {
        Sanctum::actingAs($this->sourceUser);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('id', $this->transfer->id)
                    ->where('type', 'sent')
                    ->where('status', $this->transfer->status)
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
            );
    }

    public function test_response_should_contain_source_and_target_users(): void
    {
        Sanctum::actingAs($this->sourceUser);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('source_user')
                    ->where('source_user.id', $this->sourceUser->id)
                    ->where('source_user.name', $this->sourceUser->name)
                    ->where('source_user.cpf', Masks::maskCpf($this->sourceUser->cpf))
                    ->has('source_user.created_at')

                    ->has('target_user')
                    ->where('target_user.id', $this->targetUser->id)
                    ->where('target_user.name', $this->targetUser->name)
                    ->where('target_user.cpf', Masks::maskCpf($this->targetUser->cpf))
                    ->has('target_user.created_at')

                    ->etc()
            );
    }

    public function test_response_should_contain_device_with_model_and_brand(): void
    {
        Sanctum::actingAs($this->sourceUser);

        $device = $this->transfer->device;
        $model = $device->deviceModel;
        $brand = $model->brand;

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('device')
                    ->where('device.id', $device->id)
                    ->where('device.color', $device->color)
                    ->where('device.validation_status', $device->validation_status)

                    ->has('device.model')
                    ->where('device.model.id', $model->id)
                    ->where('device.model.name', $model->name)
                    ->where('device.model.ram', $model->ram)
                    ->where('device.model.storage', $model->storage)

                    ->has('device.model.brand')
                    ->where('device.model.brand.id', $brand->id)
                    ->where('device.model.brand.name', $brand->name)

                    ->has('device.validated_attributes')
                    ->has('device.updated_at')

                    ->etc()
            );
    }
}
