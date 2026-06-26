<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

class DeviceShareViewException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_share';
    }

    public function action(): string
    {
        return 'view';
    }

    public function defaultMessage(): string
    {
        return 'Failed to retrieve the device record.';
    }
}
