<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

class UserMustNotTransferToSelfException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'must_not_transfer_to_self';
    }

    public function defaultMessage(): string
    {
        return 'User must not transfer to self.';
    }
}
