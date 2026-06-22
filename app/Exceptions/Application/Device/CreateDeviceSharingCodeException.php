<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

class CreateDeviceSharingCodeException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device_sharing';
    }

    public function action(): string
    {
        return 'create';
    }

    public function defaultMessage(): string
    {
        return 'Failed to create device sharing code.';
    }
}
