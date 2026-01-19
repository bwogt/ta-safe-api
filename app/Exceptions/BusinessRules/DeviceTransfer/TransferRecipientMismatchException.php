<?php

namespace App\Exceptions\BusinessRules\DeviceTransfer;

use App\Exceptions\BusinessRules\BusinessRuleException;

final class TransferRecipientMismatchException extends BusinessRuleException
{
    public function domain(): string
    {
        return 'device_transfer';
    }

    public function ruleViolated(): string
    {
        return 'recipient_mismatch';
    }

    public function defaultMessage(): string
    {
        return 'Transfer recipient mismatch.';
    }
}
