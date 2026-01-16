<?php

namespace App\Dto\DeviceTransfer;

use App\Models\Device;
use App\Models\User;

final class CreateDeviceTransferDTO
{
    public function __construct(
        public readonly Device $device,
        public readonly User $targetUser,
    ) {}
}
