<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

class PasswordResetAttemptExceededException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'password_reset_attempts_exceeded';
    }

    public function defaultMessage(): string
    {
        return 'User exceeded the maximum number of password reset attempts.';
    }
}
