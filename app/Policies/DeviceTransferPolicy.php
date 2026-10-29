<?php

namespace App\Policies;

use App\Models\DeviceTransfer;
use App\Models\User;

final class DeviceTransferPolicy
{
    public function accessAsTargetUser(User $user, DeviceTransfer $transfer): bool
    {
        return $user->id == $transfer->target_user_id;
    }

    public function accessAsSourceUser(User $user, DeviceTransfer $transfer): bool
    {
        return $user->id == $transfer->source_user_id;
    }

    public function accessAsParticipant(User $user, DeviceTransfer $transfer): bool
    {
        return $user->id === $transfer->sourceUser->id
            || $user->id === $transfer->targetUser->id;
    }
}
