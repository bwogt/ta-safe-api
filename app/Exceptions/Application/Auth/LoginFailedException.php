<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

class LoginFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'login';
    }

    public function defaultMessage(): string
    {
        return 'Login failed.';
    }
}
