<?php

namespace App\Exceptions\Application\DeviceTransfer;

use App\Exceptions\Application\ApplicationFailsException;

final class CancelDeviceTransferFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function action(): string
    {
        return 'cancel';
    }

    public function defaultMessage(): string
    {
        return 'Failed to cancel device transfer.';
    }
}
