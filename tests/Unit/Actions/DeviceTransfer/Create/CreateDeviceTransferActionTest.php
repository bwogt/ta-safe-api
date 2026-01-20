<?php

namespace Tests\Unit\Actions\DeviceTransfer\Create;

use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\CreateDeviceTransferFailedException;
use App\Exceptions\BusinessRules\Device\InvalidDeviceStateException;
use App\Exceptions\BusinessRules\Device\UserNotOwnerException;
use App\Exceptions\BusinessRules\DeviceTransfer\DeviceHasPendingTransferException;
use App\Exceptions\BusinessRules\DeviceTransfer\SelfTransferNotAllowedException;
use App\Models\DeviceTransfer;
use Database\Factories\DeviceFactory;
use Database\Factories\DeviceTransferFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CreateDeviceTransferActionTest extends CreateDeviceTransferActionTestSetUp
{
    public function test_should_return_a_instance_of_the_device_transfer(): void
    {
        $transfer = ($this->action)($this->sourceUser, $this->data());
        $this->assertInstanceOf(DeviceTransfer::class, $transfer);
    }

    public function test_should_create_a_new_device_transfer_with_pending_status(): void
    {
        $transfer = ($this->action)($this->sourceUser, $this->data());

        $this->assertDatabaseHas('device_transfers', [
            'id' => $transfer->id,
            'device_id' => $this->device->id,
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
            'status' => DeviceTransferStatus::PENDING,
        ]);
    }

    public function test_should_thrown_an_exception_when_the_source_user_is_not_owner_of_the_device(): void
    {
        $this->expectException(UserNotOwnerException::class);

        $data = $this->data(['targetUser' => $this->sourceUser]);
        ($this->action)($this->targetUser, $data);
    }

    public function test_should_thrown_an_exception_when_trying_to_create_a_transfer_for_itself(): void
    {
        $this->expectException(SelfTransferNotAllowedException::class);

        $data = $this->data(['targetUser' => $this->sourceUser]);
        ($this->action)($this->sourceUser, $data);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_pending(): void
    {
        $this->expectException(InvalidDeviceStateException::class);

        $device = DeviceFactory::new()
            ->for($this->sourceUser)
            ->create();

        $data = $this->data(['device' => $device]);
        ($this->action)($this->sourceUser, $data);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_analysis(): void
    {
        $this->expectException(InvalidDeviceStateException::class);

        $device = DeviceFactory::new()
            ->for($this->sourceUser)
            ->inAnalysis()
            ->create();

        $data = $this->data(['device' => $device]);
        ($this->action)($this->sourceUser, $data);
    }

    public function test_should_thrown_an_exception_when_the_device_status_is_rejected(): void
    {
        $this->expectException(InvalidDeviceStateException::class);

        $device = DeviceFactory::new()
            ->for($this->sourceUser)
            ->rejected()
            ->create();

        $data = $this->data(['device' => $device]);
        ($this->action)($this->sourceUser, $data);
    }

    public function test_should_thrown_an_exception_when_the_device_has_a_pending_transfer(): void
    {
        $this->expectException(DeviceHasPendingTransferException::class);

        DeviceTransferFactory::new()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
            'device_id' => $this->device->id,
        ]);

        ($this->action)($this->sourceUser, $this->data());
    }

    public function test_should_thrown_create_device_transfer_failed_exception_on_failure(): void
    {
        $this->expectException(CreateDeviceTransferFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->sourceUser, $this->data());
    }
}
