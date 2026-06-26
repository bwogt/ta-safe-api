<?php

namespace Tests\Unit\Actions\Device\Share\View;

use App\Actions\Device\Share\DeviceShareGenerateAction;
use App\Actions\Device\Share\DeviceShareViewAction;
use App\Exceptions\Application\Device\DeviceShareViewException;
use App\Exceptions\BusinessRules\Device\ShareCodeNotFoundException;
use App\Models\Device;
use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

final class DeviceShareViewActionTest extends TestCase
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

    public function test_should_return_device_when_share_code_is_valid(): void
    {
        $code = (new DeviceShareGenerateAction)($this->user, $this->device);

        $device = (new DeviceShareViewAction)($this->user, $code);
        $this->assertEquals($this->device->id, $device->id);
    }

    public function test_should_throw_exception_when_share_code_is_invalid(): void
    {
        $this->expectException(ShareCodeNotFoundException::class);
        (new DeviceShareViewAction)($this->user, 'invalid');
    }

    public function test_should_throw_domain_exception_when_redis_fails(): void
    {
        $code = '12345678';

        Redis::shouldReceive('exists')
            ->with("device_share:code:{$code}")
            ->once()
            ->andReturn(true);

        Redis::shouldReceive('get')
            ->with("device_share:code:{$code}")
            ->once()
            ->andThrow(new Exception('Redis connection error'));

        $this->expectException(DeviceShareViewException::class);
        (new DeviceShareViewAction)($this->user, $code);
    }
}
