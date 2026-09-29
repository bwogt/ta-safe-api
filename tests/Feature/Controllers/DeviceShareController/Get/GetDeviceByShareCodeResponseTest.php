<?php

namespace Tests\Feature\Controllers\DeviceShareController\Get;

use App\Utils\Masks;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class GetDeviceByShareCodeResponseTest extends GetDeviceByShareCodeTestSetUp
{
    protected function setUp(): void
    {
        parent::SetUp();
        Sanctum::actingAs($this->user);
    }

    public function test_should_return_the_expected_response_data_for_the_device(): void
    {
        $this->getJson($this->route($this->code))
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('id', $this->device->id)
                    ->where('color', $this->device->color)
                    ->where('validation_status', $this->device->validation_status->value)
                    ->has('created_at')
                    ->has('updated_at')
                    ->has('validated_attributes')
                    ->has('transfers')
                    ->etc()
            );
    }

    public function test_should_return_the_expected_response_data_for_the_device_owner(): void
    {
        $this->getJson($this->route($this->code))
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('owner.id', $this->user->id)
                    ->where('owner.name', $this->user->name)
                    ->where('owner.cpf', Masks::maskCpf($this->user->cpf))
                    ->has('owner.created_at')
                    ->etc()
            );
    }

    public function test_should_return_the_expected_response_data_for_the_device_model(): void
    {
        $this->getJson($this->route($this->code))
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->where('model.id', $this->device->deviceModel->id)
                    ->where('model.name', $this->device->deviceModel->name)
                    ->where('model.ram', $this->device->deviceModel->ram)
                    ->where('model.storage', $this->device->deviceModel->storage)
                    ->where('model.brand.id', $this->device->deviceModel->brand->id)
                    ->where('model.brand.name', $this->device->deviceModel->brand->name)
                    ->etc()
            );
    }
}
