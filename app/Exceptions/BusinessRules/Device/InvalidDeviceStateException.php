<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class InvalidDeviceStateException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'invalid_device_state';
    }

    public function defaultMessage(): string
    {
        return 'Invalid device state';
    }
}
