<?php

namespace App\Exceptions\BusinessRules\Auth;

use App\Exceptions\BusinessRules\BusinessRuleException;

class EmailNotExistsException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'auth';
    }

    public function ruleViolated(): string
    {
        return 'email_not_exists';
    }

    public function defaultMessage(): string
    {
        return 'Email not exists';
    }
}
