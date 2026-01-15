<?php

namespace App\Exceptions\BusinessRules\Device;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class UserMustBeOwnerException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device';
    }

    public function ruleViolated(): string
    {
        return 'user_must_be_owner';
    }

    public function defaultMessage(): string
    {
        return 'The user must be the owner of the device.';
    }

}
