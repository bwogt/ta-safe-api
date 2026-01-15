<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;
class RegisterDeviceFailedException extends ApplicationFailsException
{
    public function defaultMessage(): string
    {
        return 'Device registration failed.';
    }

    public function domain(): string
    {
        return 'device';
    }

    public function action(): string
    {
        return 'register';
    }
}
