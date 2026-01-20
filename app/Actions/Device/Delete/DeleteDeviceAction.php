<?php

namespace App\Actions\Device\Delete;

use App\Actions\Validator\DeviceValidator;
use App\Exceptions\Application\Device\DeleteDeviceFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DeleteDeviceAction
{
    public function __invoke(User $user, Device $device): bool
    {
        try {
            $this->validateBusinessRules($user, $device);

            return DB::transaction(function () use ($user, $device) {
                $this->deleteDevice($device);
                $this->logSuccess($user, $device);

                return true;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleFailure($e, $user, $device);
        }
    }

    private function validateBusinessRules(User $user, Device $device): void
    {
        DeviceValidator::mustBeOwner($user, $device);
        DeviceValidator::statusMustBeRejected($device);
    }

    private function deleteDevice(Device $device): void
    {
        $device->delete();
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info('Device successfully deleted.', [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, Device $device): never
    {
        throw new DeleteDeviceFailedException(
            previous: $e,
            context: [
                'user_id' => $user->id,
                'device_id' => $device->id,
            ]
        );
    }
}
