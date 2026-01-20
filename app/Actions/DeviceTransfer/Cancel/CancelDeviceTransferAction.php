<?php

namespace App\Actions\DeviceTransfer\Cancel;

use App\Actions\Validator\DeviceTransferValidator;
use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\CancelDeviceTransferFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\DeviceTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CancelDeviceTransferAction
{
    public function __invoke(User $user, DeviceTransfer $transfer): DeviceTransfer
    {
        try {
            $this->validateBusinessRules($user, $transfer);

            return DB::transaction(function () use ($user, $transfer) {
                $this->cancelTransfer($transfer);
                $this->logSuccess($user, $transfer);

                return $transfer;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleFailure($e, $user, $transfer);
        }
    }

    private function validateBusinessRules(User $user, DeviceTransfer $transfer): void
    {
        DeviceTransferValidator::mustBeSender($user, $transfer);
        DeviceTransferValidator::mustBePending($transfer);
    }

    private function cancelTransfer(DeviceTransfer $transfer): void
    {
        $transfer->update(['status' => DeviceTransferStatus::CANCELED]);
    }

    private function logSuccess(User $user, DeviceTransfer $transfer): void
    {
        Log::info('Device transfer successfully canceled.', [
            'user_id' => $user->id,
            'transfer_id' => $transfer->id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, DeviceTransfer $transfer): never
    {
        throw new CancelDeviceTransferFailedException(
            previous: $e,
            context: [
                'user_id' => $user->id,
                'transfer_id' => $transfer->id,
            ]
        );
    }
}
