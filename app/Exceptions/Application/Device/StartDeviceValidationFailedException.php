<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

class StartDeviceValidationFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device';
    }

    public function action(): string
    {
        return 'validate';
    }

    public function defaultMessage(): string
    {
        return 'Failed to start device validation.';
    }
}
