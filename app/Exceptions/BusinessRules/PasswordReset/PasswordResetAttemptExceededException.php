<?php

namespace App\Exceptions\BusinessRules\PasswordReset;

use App\Exceptions\BusinessRules\BusinessRuleException;

class PasswordResetAttemptExceededException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function ruleViolated(): string
    {
        return 'attempts_exceeded';
    }

    public function defaultMessage(): string
    {
        return 'User exceeded the maximum number of password reset attempts.';
    }
}
