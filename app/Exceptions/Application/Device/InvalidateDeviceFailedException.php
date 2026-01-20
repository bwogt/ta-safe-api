<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

class InvalidateDeviceFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device';
    }

    public function action(): string
    {
        return 'invalidate';
    }

    public function defaultMessage(): string
    {
        return 'Failed to invalidate device.';
    }
}
