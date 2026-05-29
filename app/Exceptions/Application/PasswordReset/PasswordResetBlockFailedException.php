<?php

namespace App\Exceptions\Application\PasswordReset;

use App\Exceptions\Application\ApplicationFailsException;

final class PasswordResetBlockFailedException extends ApplicationFailsException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function action(): string
    {
        return 'block';
    }

    public function defaultMessage(): string
    {
        return 'Failed to add temporary password reset block.';
    }
}
