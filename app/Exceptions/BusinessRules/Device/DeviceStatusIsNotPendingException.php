<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class DeviceStatusIsNotPendingException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'status_must_be_pending';
    }

    public function defaultMessage(): string
    {
        return 'The device validation status must be pending.';
    }
}
