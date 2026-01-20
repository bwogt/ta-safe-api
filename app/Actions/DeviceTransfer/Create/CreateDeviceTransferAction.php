<?php

namespace App\Actions\DeviceTransfer\Create;

use App\Actions\Validator\DeviceTransferValidator;
use App\Actions\Validator\DeviceValidator;
use App\Dto\DeviceTransfer\CreateDeviceTransferDTO;
use App\Exceptions\Application\DeviceTransfer\CreateDeviceTransferFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\DeviceTransfer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateDeviceTransferAction
{
    public function __invoke(User $user, CreateDeviceTransferDTO $data): DeviceTransfer
    {
        try {
            $this->validateBusinessRules($user, $data);

            return DB::transaction(function () use ($user, $data) {
                $transfer = $this->createDeviceTransfer($user, $data);
                $this->logSuccess($transfer);

                return $transfer;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleFailure($e, $user, $data);
        }
    }

    private function validateBusinessRules(User $user, CreateDeviceTransferDTO $data): void
    {
        DeviceValidator::mustBeOwner($user, $data->device);
        DeviceValidator::statusMustBeValidated($data->device);

        DeviceTransferValidator::mustNotTransferToSelf($user, $data->targetUser);
        DeviceTransferValidator::mustBeAvailableForTransfer($data->device);
    }

    private function createDeviceTransfer(User $user, CreateDeviceTransferDTO $data): DeviceTransfer
    {
        return DeviceTransfer::create([
            'device_id' => $data->device->id,
            'source_user_id' => $user->id,
            'target_user_id' => $data->targetUser->id,
        ]);
    }

    private function logSuccess(DeviceTransfer $transfer): void
    {
        Log::info('Device transfer successfully created.', [
            'transfer_id' => $transfer->id,
            'user_id' => $transfer->source_user_id,
            'target_user_id' => $transfer->target_user_id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, CreateDeviceTransferDTO $data): never
    {
        throw new CreateDeviceTransferFailedException(
            previous: $e,
            context: [
                'device_id' => $data->device->id,
                'user_id' => $user->id,
                'target_user_id' => $data->targetUser->id,
            ]
        );
    }
}
