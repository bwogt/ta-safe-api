<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class DeviceHasPendingTransferException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'has_pending_transfer';
    }

    public function defaultMessage(): string
    {
        return 'Device has pending transfer.';
    }
}
