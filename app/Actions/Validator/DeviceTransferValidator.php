<?php

namespace App\Actions\Validator;

use App\Exceptions\BusinessRules\DeviceTransfer\DeviceHasPendingTransferException;
use App\Exceptions\BusinessRules\DeviceTransfer\SelfTransferNotAllowedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferCannotBeModifiedException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferRecipientMismatchException;
use App\Exceptions\BusinessRules\DeviceTransfer\TransferSenderMismatchException;
use App\Models\Device;
use App\Models\DeviceTransfer;
use App\Models\User;

class DeviceTransferValidator
{
    public static function mustNotTransferToSelf(User $sourceUser, User $targetUser): void
    {
        $isSelfTransfer = $sourceUser->id === $targetUser->id;

        throw_if($isSelfTransfer, new SelfTransferNotAllowedException([
            'source_user_id' => $sourceUser->id,
            'target_user_id' => $targetUser->id,
        ]));
    }

    public static function mustBeAvailableForTransfer(Device $device): void
    {
        $transfer = $device->lastTransfer();

        throw_if($transfer?->status->isPending(),
            new DeviceHasPendingTransferException(['device_id' => $device->id]));
    }

    public static function mustBeSender(User $user, DeviceTransfer $transfer): void
    {
        $isSender = $user->id === $transfer->source_user_id;

        throw_unless($isSender, new TransferSenderMismatchException([
            'user_id' => $user->id,
            'transfer_id' => $transfer->id,
        ]));
    }

    public static function mustBeTheRecipient(User $user, DeviceTransfer $transfer): void
    {
        $isNotRecipient = $user->id !== $transfer->target_user_id;

        throw_if($isNotRecipient, new TransferRecipientMismatchException([
            'user_id' => $user->id,
            'transfer_id' => $transfer->id,
        ]));
    }

    public static function mustBePending(DeviceTransfer $transfer): void
    {
        $isPending = $transfer->status->isPending();

        throw_unless($isPending, new TransferCannotBeModifiedException([
            'transfer_id' => $transfer->id,
        ]));
    }
}
