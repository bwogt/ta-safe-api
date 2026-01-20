<?php

namespace App\Actions\DeviceTransfer\Accept;

use App\Actions\Validator\DeviceTransferValidator;
use App\Enums\Device\DeviceTransferStatus;
use App\Exceptions\Application\DeviceTransfer\AcceptDeviceTransferFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\DeviceTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AcceptDeviceTransferAction
{
    public function __invoke(User $user, DeviceTransfer $transfer): DeviceTransfer
    {
        try {
            $this->validateBusinessRules($user, $transfer);

            return DB::transaction(function () use ($user, $transfer) {
                $this->updateDeviceTransfer($transfer);
                $this->updateDeviceOwner($user, $transfer);
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
        DeviceTransferValidator::mustBeTheRecipient($user, $transfer);
        DeviceTransferValidator::mustBePending($transfer);
    }

    private function updateDeviceTransfer(DeviceTransfer $transfer): void
    {
        $transfer->update(['status' => DeviceTransferStatus::ACCEPTED]);
    }

    private function updateDeviceOwner(User $user, DeviceTransfer $transfer): void
    {
        $transfer->device->update(['user_id' => $user->id]);
    }

    private function logSuccess(User $user, DeviceTransfer $transfer): void
    {
        Log::info('Device transfer successfully accepted.', [
            'user_id' => $user->id,
            'transfer_id' => $transfer->id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, DeviceTransfer $transfer): never
    {
        throw new AcceptDeviceTransferFailedException(
            previous: $e,
            context: [
                'user_id' => $user->id,
                'transfer_id' => $transfer->id,
            ]
        );
    }

}
