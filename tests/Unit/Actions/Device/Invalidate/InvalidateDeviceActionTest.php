<?php

namespace Tests\Unit\Actions\Device\Invalidate;

use App\Enums\Device\DeviceValidationStatus;
use App\Exceptions\Application\Device\InvalidateDeviceFailedException;
use App\Exceptions\BusinessRules\Device\DeviceStatusMustBePendingException;
use App\Exceptions\BusinessRules\Device\UserMustBeOwnerException;
use App\Models\Device;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InvalidateDeviceActionTest extends InvalidateDeviceActionTestSetUp
{
    public function test_should_return_a_device_instance_when_the_action_is_executed_successfully(): void
    {
        $result = ($this->action)($this->user, $this->device);
        $this->assertInstanceOf(Device::class, $result);
    }

    public function test_should_change_the_device_status_to_rejected(): void
    {
        ($this->action)($this->user, $this->device);

        $this->assertDatabaseHas('devices', [
            'id' => $this->device->id,
            'user_id' => $this->user->id,
            'validation_status' => DeviceValidationStatus::REJECTED,
        ]);
    }

    public function test_should_thrown_an_exception_when_the_user_is_not_the_device_owner(): void
    {
        $this->expectException(UserMustBeOwnerException::class);

        $nonOwnerUser = UserFactory::new()->create();
        ($this->action)($nonOwnerUser, $this->device);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_in_analysis(): void
    {
        $this->expectException(DeviceStatusMustBePendingException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->inAnalysis()
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_validated(): void
    {
        $this->expectException(DeviceStatusMustBePendingException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->validated()
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_rejected(): void
    {
        $this->expectException(DeviceStatusMustBePendingException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->rejected()
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_throw_invalidate_device_failed_exception_on_failure(): void
    {
        $this->expectException(InvalidateDeviceFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->user, $this->device);
    }
}
