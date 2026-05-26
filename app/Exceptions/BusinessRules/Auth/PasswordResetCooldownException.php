<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

class PasswordResetCooldownException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'password_reset_cooldown';
    }

    public function defaultMessage(): string
    {
        return 'Password reset is on cooldown.';
    }
}
