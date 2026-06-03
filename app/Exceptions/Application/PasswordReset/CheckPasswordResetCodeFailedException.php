<?php

namespace App\Exceptions\Application\PasswordReset;

use App\Exceptions\Application\ApplicationFailsException;

final class CheckPasswordResetCodeFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function action(): string
    {
        return 'check_code';
    }

    public function defaultMessage(): string
    {
        return 'Failed to check password reset code.';
    }
}
