<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

final class CreateDeviceShareCodeException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_share';
    }

    public function action(): string
    {
        return 'create';
    }

    public function defaultMessage(): string
    {
        return 'Failed to generate a device sharing code.';
    }
}
