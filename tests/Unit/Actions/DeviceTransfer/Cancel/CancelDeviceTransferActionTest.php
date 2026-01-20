<?php

namespace Tests\Unit\Actions\DeviceTransfer\Cancel;

use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\CancelDeviceTransferFailedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferCannotBeModifiedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferSenderMismatchException;
use App\Models\DeviceTransfer;
use Database\Factories\DeviceTransferFactory;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class CancelDeviceTransferActionTest extends CancelDeviceTransferActionTestSetUp
{
    public function test_should_return_an_instance_of_device_transfer(): void
    {
        $transfer = ($this->action)($this->sourceUser, $this->transfer);
        $this->assertInstanceOf(DeviceTransfer::class, $transfer);
    }

    public function test_should_update_the_transfer_status_to_canceled(): void
    {
        ($this->action)($this->sourceUser, $this->transfer);

        $this->assertDatabaseHas('device_transfers', [
            'id' => $this->transfer->id,
            'status' => DeviceTransferStatus::CANCELED,
        ]);
    }

    public function test_should_throw_an_exception_when_the_user_is_not_the_transfer_sender(): void
    {
        $this->expectException(TransferSenderMismatchException::class);

        ($this->action)($this->targetUser, $this->transfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_accepted(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->accepted()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->sourceUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_canceled(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->canceled()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->sourceUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_the_device_transfer_has_the_status_rejected(): void
    {
        $this->expectException(TransferCannotBeModifiedException::class);

        $transfer = DeviceTransferFactory::new()->rejected()->create([
            'source_user_id' => $this->sourceUser->id,
            'target_user_id' => $this->targetUser->id,
        ]);

        ($this->action)($this->sourceUser, $transfer);
    }

    public function test_should_thrown_an_exception_when_occurred_an_internal_error(): void
    {
        $this->expectException(CancelDeviceTransferFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->sourceUser, $this->transfer);
    }
}
