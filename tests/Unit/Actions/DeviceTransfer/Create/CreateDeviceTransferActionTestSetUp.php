<?php

namespace Tests\Unit\Actions\DeviceTransfer\Create;

use App\Actions\DeviceTransfer\Create\CreateDeviceTransferAction;
use App\Dto\DeviceTransfer\CreateDeviceTransferDTO;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class CreateDeviceTransferActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $sourceUser;
    protected User $targetUser;
    protected Device $device;
    protected CreateDeviceTransferAction $action;

    protected function setUp(): void
    {
        parent::SetUp();

        $this->userSetUp();
        $this->deviceSetUp();
        $this->actionSetUp();
    }

    private function userSetUp(): void
    {
        $this->sourceUser = UserFactory::new()->create();
        $this->targetUser = UserFactory::new()->create();
    }

    private function deviceSetUp(): void
    {
        $this->device = DeviceFactory::new()
            ->for($this->sourceUser)
            ->validated()
            ->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new CreateDeviceTransferAction;
    }

    protected function data($override = []): CreateDeviceTransferDTO
    {
        return new CreateDeviceTransferDTO(
            device: $override['device'] ?? $this->device,
            targetUser: $override['targetUser'] ?? $this->targetUser
        );
    }
}
