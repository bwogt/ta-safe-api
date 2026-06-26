<?php

namespace Tests\Feature\Controllers\DeviceShareController\Get;

use App\Actions\Device\Share\CreateDeviceShareCodeAction;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\Feature\Asserts\AccessAsserts;
use Tests\TestCase;

class GetDeviceByShareCodeTestSetUp extends TestCase
{
    use AccessAsserts;
    use RefreshDatabase;

    protected User $user;
    protected Device $device;
    protected string $code;

    protected function setUp(): void
    {
        parent::SetUp();
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

        $this->code = (new CreateDeviceShareCodeAction)($this->user, $this->device);
    }

    protected function route(?string $code = null): string
    {
        return route('api.device.share.view', ['code' => $code]);
    }
}
