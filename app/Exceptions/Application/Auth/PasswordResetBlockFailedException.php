<?php

namespace App\Exceptions\Application\Auth;

use App\Exceptions\Application\ApplicationFailsException;

final class PasswordResetBlockFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function action(): string
    {
        return 'password_reset_block';
    }

    public function defaultMessage(): string
    {
        return 'Failed to add temporary password reset block.';
    }
}
