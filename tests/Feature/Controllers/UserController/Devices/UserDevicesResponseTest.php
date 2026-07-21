<?php

namespace Tests\Feature\Controllers\UserController\Devices;

use App\Enums\Device\DeviceValidationStatus;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class UserDevicesResponseTest extends UserDevicesTestSetUp
{
    public function test_an_authenticated_user_can_view_their_devices_by_status(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson($this->route(status: DeviceValidationStatus::PENDING))
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 1)
                    ->has('data.0', fn (AssertableJson $json) => $json
                        ->where('id', $this->device->id)
                        ->where('color', $this->device->color)
                        ->where('imei_1', $this->device->imei_1)
                        ->where('imei_2', $this->device->imei_2)
                        ->where('access_key', $this->device->invoice->access_key)
                        ->where('validation_status', $this->device->validation_status->value)
                        ->where('share_code', null)
                        ->has('created_at')
                        ->has('updated_at')
                        ->missing('user.password')
                        ->where('model.name', $this->device->deviceModel->name)
                        ->where('model.ram', $this->device->deviceModel->ram)
                        ->where('model.storage', $this->device->deviceModel->storage)
                        ->where('model.brand.name', $this->device->deviceModel->brand->name)
                        ->has('validated_attributes')
                        ->has('transfers')
                    )
                    ->has('meta')
                    ->where('meta.current_page', 1)
                    ->where('meta.last_page', 1)
                    ->where('meta.per_page', 4)
                    ->where('meta.has_next_page', false)
                    ->where('meta.total', 1)
            );
    }
}
