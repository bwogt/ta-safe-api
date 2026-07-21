<?php

namespace App\Actions\User\Devices;

use App\Enums\Device\DeviceValidationStatus;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class DevicesByStatusAction
{
    public function __invoke(
        User $user,
        DeviceValidationStatus $status,
        ?int $perPage = 4
    ): LengthAwarePaginator {
        return $user->devices()
            ->whereValidationStatus($status->value)
            ->with([
                'transfers',
                'deviceModel.brand',
                'attributeValidationLogs',
            ])
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }
}
