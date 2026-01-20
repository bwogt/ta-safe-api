<?php

namespace Tests\Unit\Actions\Device\Validate;

use App\Actions\Device\Validate\StartDeviceValidationAction;
use App\Dto\Device\DeviceInvoiceDTO;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class StartDeviceValidationActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Device $device;
    protected DeviceInvoiceDTO $data;
    protected StartDeviceValidationAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->actionSetUp();
        $this->deviceSetUp();
        $this->dataSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new StartDeviceValidationAction;
    }

    private function deviceSetUp(): void
    {
        $this->device = DeviceFactory::new()
            ->for($this->user)
            ->create();
    }

    private function dataSetUp(): void
    {
        $products = "{$this->device->deviceModel->brand->name} "
            . " {$this->device->deviceModel->name} "
            . " {$this->device->color} "
            . " {$this->device->deviceModel->storage} "
            . " {$this->device->deviceModel->ram} ";

        $this->data = new DeviceInvoiceDTO(
            name: $this->user->name,
            cpf: $this->user->cpf,
            products: $products
        );
    }
}
