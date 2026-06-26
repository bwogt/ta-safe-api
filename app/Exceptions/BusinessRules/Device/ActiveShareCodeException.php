<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class ActiveShareCodeException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'active_share';
    }

    public function defaultMessage(): string
    {
        return 'Device already has an active share code.';
    }
}
