<?php

namespace Tests\Feature\Controllers\UserController\Devices;

use App\Enums\Device\DeviceValidationStatus;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class UserDevicesResponseTest extends UserDevicesTestSetUp
{
    public function test_an_authenticated_user_can_view_their_devices_summary_by_status(): void
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
                        ->where('validation_status', $this->device->validation_status->value)
                        ->has('model.id')
                        ->where('model.name', $this->device->deviceModel->name)
                        ->where('model.ram', $this->device->deviceModel->ram)
                        ->where('model.storage', $this->device->deviceModel->storage)
                        ->has('model.brand.id')
                        ->where('model.brand.name', $this->device->deviceModel->brand->name)
                        ->has('validated_attributes')
                        ->has('updated_at')
                    )
                    ->has('meta')
                    ->has('meta.next_cursor')
                    ->where('meta.has_more_page', false)
            );
    }
}
