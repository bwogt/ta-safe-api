<?php

namespace App\Actions\DeviceTransfer\Reject;

use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\RejectDeviceTransferFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Guards\DeviceTransferGuard;
use App\Models\DeviceTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RejectDeviceTransferAction
{
    public function __invoke(User $user, DeviceTransfer $transfer): DeviceTransfer
    {
        try {
            $this->enforceBusinessRules($user, $transfer);

            return DB::transaction(function () use ($user, $transfer) {
                $this->rejectTransfer($transfer);
                $this->logSuccess($user, $transfer);

                return $transfer;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new RejectDeviceTransferFailedException(
                previous: $e,
                context: [
                    'user_id' => $user->id,
                    'transfer_id' => $transfer->id,
                ]
            );
        }
    }

    private function enforceBusinessRules(User $user, DeviceTransfer $transfer): void
    {
        DeviceTransferGuard::mustBeTheRecipient($user, $transfer);
        DeviceTransferGuard::mustBePending($transfer);
    }

    private function rejectTransfer(DeviceTransfer $transfer): void
    {
        $transfer->update(['status' => DeviceTransferStatus::REJECTED]);
    }

    private function logSuccess(User $user, DeviceTransfer $transfer): void
    {
        Log::info('Device transfer successfully rejected.', [
            'user_id' => $user->id,
            'transfer_id' => $transfer->id,
        ]);
    }
}
