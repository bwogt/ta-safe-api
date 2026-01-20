<?php

namespace App\Exceptions\Application\Device;

use App\Exceptions\Application\ApplicationFailsException;

class CreateSharingTokenFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'device';
    }

    public function action(): string
    {
        return 'create_sharing_token';
    }

    public function defaultMessage(): string
    {
        return 'Failed to create device sharing token.';
    }
}
