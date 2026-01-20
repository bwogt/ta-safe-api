<?php

namespace App\Exceptions\Application\DeviceTransfer;

use App\Exceptions\Application\ApplicationFailsException;

class RejectDeviceTransferFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function action(): string
    {
        return 'reject';
    }

    public function defaultMessage(): string
    {
        return 'Device transfer reject action failed.';
    }
}
