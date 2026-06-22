<?php

namespace Tests\Unit\Actions\Device\Share;

use App\Actions\Device\Share\CreateDeviceSharingCodeAction;
use App\Exceptions\Application\Device\CreateDeviceSharingCodeException;
use App\Exceptions\BusinessRules\Device\ActiveShareCodeException;
use App\Exceptions\BusinessRules\Device\InvalidDeviceStateException;
use App\Exceptions\BusinessRules\Device\UserNotOwnerException;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Support\Facades\Redis;
use RuntimeException;

final class DeviceShareCodeActionTest extends DeviceShareCodeActionTestSetUp
{
    public function test_should_create_a_device_share_code(): void
    {
        $code = (new CreateDeviceSharingCodeAction)($this->user, $this->device);
        $cachedCode = Redis::get("device:{$this->device->id}:active_share");

        $this->assertEquals($code, $cachedCode);
        $this->assertMatchesRegularExpression('/^\d{8}$/', $code);
    }

    public function test_should_create_a_device_active_share_state(): void
    {
        $code = (new CreateDeviceSharingCodeAction)($this->user, $this->device);
        $cachedDeviceId = (int) Redis::get("device_share:code:{$code}");

        $this->assertEquals($this->device->id, $cachedDeviceId);
    }

    public function test_should_set_one_day_ttl_for_active_share_key(): void
    {
        (new CreateDeviceSharingCodeAction)($this->user, $this->device);
        $ttl = Redis::ttl("device:{$this->device->id}:active_share");

        $this->assertGreaterThan(86300, $ttl);
        $this->assertLessThanOrEqual(86400, $ttl);
    }

    public function test_should_set_one_day_ttl_for_share_code_key(): void
    {
        $code = (new CreateDeviceSharingCodeAction)($this->user, $this->device);
        $ttl = Redis::ttl("device_share:code:{$code}");

        $this->assertGreaterThan(86300, $ttl);
        $this->assertLessThanOrEqual(86400, $ttl);
    }

    public function test_should_throw_exception_when_user_is_not_device_owner(): void
    {
        $user = UserFactory::new()->create();

        try {
            (new CreateDeviceSharingCodeAction)($user, $this->device);
            $this->fail('Expected UserNotOwnerException was not thrown.');
        } catch (UserNotOwnerException $e) {
            $this->assertFalse((bool) Redis::exists("device:{$this->device->id}:active_share"));
        }
    }

    public function test_should_throw_exception_when_device_status_is_pending(): void
    {
        $device = DeviceFactory::new()->for($this->user)->create();

        try {
            (new CreateDeviceSharingCodeAction)($this->user, $device);
            $this->fail('Expected InvalidDeviceStateException was not thrown.');
        } catch (InvalidDeviceStateException $e) {
            $this->assertFalse((bool) Redis::exists("device:{$device->id}:active_share"));
        }
    }

    public function test_should_throw_exception_when_device_status_is_in_analysis(): void
    {
        $device = DeviceFactory::new()->for($this->user)->inAnalysis()->create();

        try {
            (new CreateDeviceSharingCodeAction)($this->user, $device);
            $this->fail('Expected InvalidDeviceStateException was not thrown.');
        } catch (InvalidDeviceStateException $e) {
            $this->assertFalse((bool) Redis::exists("device:{$device->id}:active_share"));
        }
    }

    public function test_should_throw_exception_when_device_status_is_rejected(): void
    {
        $device = DeviceFactory::new()->for($this->user)->rejected()->create();

        try {
            (new CreateDeviceSharingCodeAction)($this->user, $device);
            $this->fail('Expected InvalidDeviceStateException was not thrown.');
        } catch (InvalidDeviceStateException $e) {
            $this->assertFalse((bool) Redis::exists("device:{$device->id}:active_share"));
        }
    }

    public function test_should_throw_exception_when_device_already_has_active_share_code(): void
    {
        $code = (new CreateDeviceSharingCodeAction)($this->user, $this->device);

        try {
            (new CreateDeviceSharingCodeAction)($this->user, $this->device);
            $this->fail('Expected ActiveShareCodeException was not thrown.');
        } catch (ActiveShareCodeException $e) {
            $this->assertTrue((bool) Redis::exists("device_share:code:{$code}"));
            $this->assertTrue((bool) Redis::exists("device:{$this->device->id}:active_share"));
        }
    }

    public function test_should_throw_domain_exception_when_redis_fails_during_validation(): void
    {
        Redis::shouldReceive('get')
            ->once()
            ->andThrow(new Exception('Redis get error'));

        $this->expectException(CreateDeviceSharingCodeException::class);
        (new CreateDeviceSharingCodeAction)($this->user, $this->device);
    }

    public function test_should_throw_domain_exception_when_redis_eval_fails(): void
    {
        Redis::shouldReceive('get')->andReturn(null);

        Redis::shouldReceive('eval')
            ->once()
            ->andThrow(new Exception('Redis connection timeout'));

        $this->expectException(CreateDeviceSharingCodeException::class);
        (new CreateDeviceSharingCodeAction)($this->user, $this->device);
    }

    public function test_should_throw_domain_exception_when_max_generation_attempts_reached(): void
    {
        Redis::shouldReceive('get')->andReturn(null);
        Redis::shouldReceive('eval')->times(5)->andReturn(0);

        try {
            (new CreateDeviceSharingCodeAction)($this->user, $this->device);
            $this->fail('Expected CreateDeviceSharingCodeException was not thrown.');
        } catch (CreateDeviceSharingCodeException $e) {
            $this->assertInstanceOf(RuntimeException::class, $e->getPrevious());
            $this->assertEquals(
                'Maximum attempts reached while generating device share code.',
                $e->getPrevious()->getMessage()
            );
        }
    }

    public function test_should_retry_generating_code_when_collision_occurs(): void
    {
        Redis::shouldReceive('get')->andReturn(null);
        Redis::shouldReceive('eval')->times(3)->andReturn(0, 0, 1);

        $code = (new CreateDeviceSharingCodeAction)($this->user, $this->device);
        $this->assertMatchesRegularExpression('/^\d{8}$/', $code);
    }
}
