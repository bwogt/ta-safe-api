<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

class PasswordResetBlockedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'password_reset_blocked';
    }

    public function defaultMessage(): string
    {
        return 'Password reset code has been blocked.';
    }
}
