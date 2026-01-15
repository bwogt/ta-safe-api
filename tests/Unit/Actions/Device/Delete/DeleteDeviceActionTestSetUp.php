<?php

namespace Tests\Unit\Actions\Device\Delete;

use App\Actions\Device\Delete\DeleteDeviceAction;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class DeleteDeviceActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Device $device;
    protected DeleteDeviceAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->deviceSetUp();
        $this->actionSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function deviceSetUp(): void
    {
        $this->device = DeviceFactory::new()
            ->for($this->user)
            ->rejected()
            ->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new DeleteDeviceAction;
    }

}
