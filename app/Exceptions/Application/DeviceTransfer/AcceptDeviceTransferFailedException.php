<?php

namespace App\Exceptions\Application\DeviceTransfer;

use App\Exceptions\Application\ApplicationFailsException;

class AcceptDeviceTransferFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function action(): string
    {
        return 'accept';
    }

    public function defaultMessage(): string
    {
        return 'Device transfer accept action failed.';
    }
}
