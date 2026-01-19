<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class TransferSenderMismatchException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'sender_mismatch';
    }

    public function defaultMessage(): string
    {
        return 'Transfer sender mismatch.';
    }
}
