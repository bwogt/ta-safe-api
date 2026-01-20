<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

final class DeleteDeviceFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device';
    }

    public function action(): string
    {
        return 'delete';
    }

    public function defaultMessage(): string
    {
        return 'Device delete failed.';
    }
}
