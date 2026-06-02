<?php

namespace App\Exceptions\BusinessRules\PasswordReset;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class InvalidPasswordResetCodeException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function ruleViolated(): string
    {
        return 'invalid_code';
    }

    public function defaultMessage(): string
    {
        return 'Invalid password reset code.';
    }
}
