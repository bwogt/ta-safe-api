<?php

namespace Tests\Unit\Models\Device\ActiveShareCode;

use App\Actions\Device\Share\CreateDeviceShareCodeAction;
use App\Dto\Device\DeviceShareCodeDTO;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

final class DeviceActiveShareCodeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Device $device;

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

    public function test_should_return_null_when_device_has_no_active_share_code(): void
    {
        $this->assertNull($this->device->activeShareCode());
    }

    public function test_should_return_device_share_code_dto_when_active_share_code_exists(): void
    {
        (new CreateDeviceShareCodeAction)($this->user, $this->device);
        $shareCode = $this->device->activeShareCode();

        $this->assertInstanceOf(DeviceShareCodeDTO::class, $shareCode);
    }

    public function test_should_return_active_share_code_with_expected_code()
    {
        $code = (new CreateDeviceShareCodeAction)($this->user, $this->device);
        $shareCode = $this->device->activeShareCode();

        $this->assertEquals($shareCode->code, $code);
    }

    public function test_should_return_active_share_code_with_expected_ttl()
    {
        (new CreateDeviceShareCodeAction)($this->user, $this->device);
        $shareCode = $this->device->activeShareCode();

        $this->assertGreaterThan(86300, $shareCode->ttl);
        $this->assertLessThanOrEqual(86400, $shareCode->ttl);
    }

}
