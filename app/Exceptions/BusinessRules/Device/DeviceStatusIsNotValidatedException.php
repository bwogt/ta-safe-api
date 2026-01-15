<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

class DeviceStatusIsNotValidatedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'status_is_not_validated';
    }

    public function defaultMessage(): string
    {
        return 'Device register status is not validated';
    }
}
