<?php

namespace App\Exceptions\BusinessRules\PasswordReset;

use App\Exceptions\BusinessRules\BusinessRuleException;

class PasswordResetBlockedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'password_reset';
    }

    public function ruleViolated(): string
    {
        return 'blocked';
    }

    public function defaultMessage(): string
    {
        return 'The email has a temporary password reset block.';
    }
}
