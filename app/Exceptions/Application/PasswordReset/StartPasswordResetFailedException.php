<?php

namespace App\Exceptions\Application\PasswordReset;

use App\Exceptions\Application\ApplicationFailsException;

class StartPasswordResetFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function action(): string
    {
        return 'start';
    }

    public function defaultMessage(): string
    {
        return 'Failed to generate a password reset code.';
    }
}
