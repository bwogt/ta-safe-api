<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class DeviceStatusIsNotRejectedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'status_must_be_rejected';
    }

    public function defaultMessage(): string
    {
        return 'Device status must be rejected.';
    }
}
