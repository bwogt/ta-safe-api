<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

final class RegisterUserFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'register';
    }

    public function defaultMessage(): string
    {
        return 'Auth register action failed.';
    }
}
