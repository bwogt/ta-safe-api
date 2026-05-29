<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class InvalidPasswordResetCodeException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'invalid_password_reset_code';
    }

    public function defaultMessage(): string
    {
        return 'Invalid password reset code.';
    }
}
