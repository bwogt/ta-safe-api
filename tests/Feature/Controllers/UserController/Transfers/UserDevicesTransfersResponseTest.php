<?php

namespace Tests\Feature\Controllers\UserController\Transfers;

use App\Enums\Device\DeviceValidationStatus;
use App\Utils\Masks;
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
                        ->has('updated_at')
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

    public function test_should_return_source_and_target_users(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1, fn (AssertableJson $data) => $data
                        ->has('source_user')
                        ->where('source_user.id', $this->user->id)
                        ->where('source_user.name', $this->user->name)
                        ->where('source_user.email', $this->user->email)
                        ->where('source_user.cpf', Masks::maskCpf($this->user->cpf))
                        ->has('created_at')

                        ->has('target_user')
                        ->where('target_user.id', $this->targetUser->id)
                        ->where('target_user.name', $this->targetUser->name)
                        ->where('target_user.email', $this->targetUser->email)
                        ->where('target_user.cpf', Masks::maskCpf($this->targetUser->cpf))
                        ->has('created_at')

                        ->etc()
                    )
                    ->etc()
            );
    }

    public function test_should_return_device_data(): void
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
                        ->has('device')
                        ->where('device.id', $device->id)
                        ->where('device.color', $device->color)
                        ->where('device.validation_status', DeviceValidationStatus::VALIDATED->value)

                        ->has('device.model')
                        ->where('device.model.id', $model->id)
                        ->where('device.model.name', $model->name)
                        ->where('device.model.ram', $model->ram)
                        ->where('device.model.storage', $model->storage)

                        ->has('device.model.brand')
                        ->where('device.model.brand.id', $brand->id)
                        ->where('device.model.brand.name', $brand->name)

                        ->has('device.updated_at')
                        ->etc()
                    )
                    ->etc()
            );
    }
}
