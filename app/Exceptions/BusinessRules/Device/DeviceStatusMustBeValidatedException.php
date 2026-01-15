<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class DeviceStatusMustBeValidatedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'status_must_be_validated';
    }

    public function defaultMessage(): string
    {
        return 'The device status must be validated.';
    }
}
