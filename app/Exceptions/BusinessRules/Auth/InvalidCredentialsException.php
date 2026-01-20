<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

class InvalidCredentialsException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'invalid_credentials';
    }

    public function defaultMessage(): string
    {
        return 'Invalid credentials.';
    }
}
