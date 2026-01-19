<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class TransferCannotBeModifiedException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'cannot_be_modified';
    }

    public function defaultMessage(): string
    {
        return 'Transfer cannot be modified.';
    }
}
