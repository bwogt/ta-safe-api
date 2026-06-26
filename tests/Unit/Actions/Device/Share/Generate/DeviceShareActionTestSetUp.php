<?php

namespace Tests\Unit\Actions\Device\Share\Generate;

use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeviceShareActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected Device $device;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Redis::flushdb();

        $this->userSetUp();
        $this->deviceSetUp();
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
}
