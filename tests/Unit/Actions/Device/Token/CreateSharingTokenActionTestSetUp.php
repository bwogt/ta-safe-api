<?php

namespace Tests\Unit\Actions\Device\Token;

use App\Actions\Device\Token\CreateSharingTokenAction;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class CreateSharingTokenActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Device $device;
    protected CreateSharingTokenAction $action;

    protected function setUp(): void
    {
        parent::SetUp();

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
            ->validated()
            ->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new CreateSharingTokenAction;
    }
}
