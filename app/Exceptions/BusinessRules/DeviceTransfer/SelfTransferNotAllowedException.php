<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

class SelfTransferNotAllowedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'self_transfer_not_allowed';
    }

    public function defaultMessage(): string
    {
        return 'User must not transfer to self.';
    }
}
