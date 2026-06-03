<?php

namespace App\Exceptions\Application\PasswordReset;

use App\Exceptions\Application\ApplicationFailsException;

final class ResetPasswordFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function action(): string
    {
        return 'reset';
    }

    public function defaultMessage(): string
    {
        return 'Failed to reset the user password.';
    }
}
