<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class ShareCodeNotFoundException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'share_code_not_found';
    }

    public function defaultMessage(): string
    {
        return 'Invalid device share code.';
    }
}
