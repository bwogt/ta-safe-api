<?php

namespace App\Services\DeviceTransfer;

use App\Actions\DeviceTransfer\Cancel\CancelDeviceTransferAction;
use App\Actions\DeviceTransfer\Reject\RejectDeviceTransferAction;
use App\Models\DeviceTransfer;
use App\Models\User;

class DeviceTransferService
{
    public function __construct(
        private readonly User $user
    ) {}

    /**
     * Cancel a device transfer.
     */
    public function cancel(DeviceTransfer $transfer): DeviceTransfer
    {
        return (new CancelDeviceTransferAction($this->user, $transfer))->execute();
    }

    /**
     * Reject a device transfer.
     */
    public function reject(DeviceTransfer $transfer): DeviceTransfer
    {
        return (new RejectDeviceTransferAction($this->user, $transfer))->execute();
    }
}
