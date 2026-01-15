<?php

namespace App\Exceptions\Application\User;

use App\Exceptions\Application\ApplicationFailsException;

class UpdateUserFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'User';
    }

    public function action(): string
    {
        return 'update';
    }

    public function defaultMessage(): string
    {
        return 'Failed to update user.';
    }
}
