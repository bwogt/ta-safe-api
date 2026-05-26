<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

class ForgotPasswordFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'forgot_password';
    }

    public function defaultMessage(): string
    {
        return 'Failed to generate a password reset code.';
    }
}
