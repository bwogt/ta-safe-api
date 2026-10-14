<?php

namespace App\Actions\User\Transfers;

use App\Enums\Device\DeviceTransferStatus;
use App\Models\DeviceTransfer;
use App\Models\User;
use Illuminate\Pagination\CursorPaginator;

final class UserTransfersAction
{
    public function __invoke(
        User $user,
        ?DeviceTransferStatus $status = null,
        ?int $perPage = 4
    ): CursorPaginator {
        return DeviceTransfer::with([
            'device.deviceModel.brand',
            'sourceUser',
            'targetUser',
        ])
            ->where(function ($query) use ($user) {
                $query->where('source_user_id', $user->id)
                    ->orWhere('target_user_id', $user->id);
            })
            ->when(
                $status != null,
                fn ($query) => $query->where('status', $status->value)
            )
            ->orderByDesc('updated_at')
            ->cursorPaginate($perPage);
    }
}
