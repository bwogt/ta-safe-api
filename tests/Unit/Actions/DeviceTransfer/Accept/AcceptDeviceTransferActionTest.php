<?php

namespace Tests\Unit\Actions\DeviceTransfer\Accept;

use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\AcceptDeviceTransferFailedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferCannotBeModifiedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferRecipientMismatchException;
use App\Models\DeviceTransfer;
use Database\Factories\DeviceTransferFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class AcceptDeviceTransferActionTest extends AcceptDeviceTransferActionTestSetUp
{
    public function test_should_return_an_instance_of_device_transfer(): void
    {
        $transfer = ($this->action)($this->targetUser, $this->deviceTransfer);
        $this->assertInstanceOf(DeviceTransfer::class, $transfer);
    }

    public function test_should_update_the_device_transfer_status_to_accepted(): void
    {
        ($this->action)($this->targetUser, $this->deviceTransfer);

        $this->assertDatabaseHas('device_transfers', [
            'id' => $this->deviceTransfer->id,
            'status' => DeviceTransferStatus::ACCEPTED,
        ]);
    }

    public function test_should_update_the_device_owner_to_the_target_user(): void
    {
        ($this->action)($this->targetUser, $this->deviceTransfer);

        $this->assertDatabaseHas('devices', [
            'id' => $this->deviceTransfer->device_id,
            'user_id' => $this->targetUser->id,
        ]);
    }

    public function test_should_thrown_an_exception_when_the_user_is_not_transfer_recipient(): void
    {
        $this->expectException(TransferRecipientMismatchException::class);
        ($this->action)($this->sourceUser, $this->deviceTransfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_accepted(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->accepted()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->targetUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_canceled(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->canceled()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->targetUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_rejected(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->rejected()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->targetUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_occurred_an_internal_error(): void
    {
        $this->expectException(AcceptDeviceTransferFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->targetUser, $this->deviceTransfer);
    }
}
