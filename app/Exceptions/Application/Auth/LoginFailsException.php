<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

class LoginFailsException extends ApplicationFailsException
{
    public function defaultMessage(): string
    {
        return 'Login failed.';
    }

    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'login';
    }
}
