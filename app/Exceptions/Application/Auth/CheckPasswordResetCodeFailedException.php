<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

final class CheckPasswordResetCodeFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'check_password_reset_code';
    }

    public function defaultMessage(): string
    {
        return 'Failed to check password reset code.';
    }
}
