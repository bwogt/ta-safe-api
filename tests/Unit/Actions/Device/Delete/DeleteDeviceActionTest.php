<?php

namespace Tests\Unit\Actions\Device\Delete;

use App\Exceptions\Application\Device\DeleteDeviceFailedException;
use App\Exceptions\BusinessRules\Device\DeviceNotOwnedException;
use App\Exceptions\BusinessRules\Device\DeviceStatusIsNotRejectedException;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;
use Symfony\Component\HttpFoundation\Response;

class DeleteDeviceActionTest extends DeleteDeviceActionTestSetUp
{
    public function test_should_return_true_when_the_device_is_deleted(): void
    {
        $this->assertTrue(($this->action)($this->user, $this->device));
    }

    public function test_should_decrement_the_total_devices_of_the_user(): void
    {
        $this->assertCount(1, $this->user->devices);

        ($this->action)($this->user, $this->device);
        $this->user->refresh();

        $this->assertCount(0, $this->user->devices);
    }

    public function test_should_not_allow_the_delete_of_a_device_that_does_not_belong_to_the_user(): void
    {
        $this->expectException(DeviceNotOwnedException::class);

        $nonOwnerUser = UserFactory::new()->create();
        ($this->action)($nonOwnerUser, $this->device);
    }

    public function test_should_not_delete_a_device_when_the_status_is_validated(): void
    {
        $this->expectException(DeviceStatusIsNotRejectedException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->validated()
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_not_delete_a_device_when_the_status_is_pending(): void
    {
        $this->expectException(DeviceStatusIsNotRejectedException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_not_delete_a_device_when_the_status_is_in_analysis(): void
    {
        $this->expectException(DeviceStatusIsNotRejectedException::class);

        $device = DeviceFactory::new()
            ->for($this->user)
            ->inAnalysis()
            ->create();

        ($this->action)($this->user, $device);
    }

    public function test_should_thrown_an_exception_when_occurs_an_internal_server_error(): void
    {
        $this->expectException(DeleteDeviceFailedException::class);

        FacadesDB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->user, $this->device);
    }
}
