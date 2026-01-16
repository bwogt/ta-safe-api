<?php

namespace App\Exceptions\Application\DeviceTransfer;

use App\Exceptions\Application\ApplicationFailsException;

class CreateDeviceTransferFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function action(): string
    {
        return 'create';
    }

    public function defaultMessage(): string
    {
        return 'Device transfer failed.';
    }
}
