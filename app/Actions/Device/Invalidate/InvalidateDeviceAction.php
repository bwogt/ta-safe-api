<?php

namespace App\Actions\Device\Invalidate;

use App\Actions\Validator\DeviceValidator;
use App\Enums\Device\DeviceValidationStatus;
use App\Exceptions\Application\Device\InvalidateDeviceFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InvalidateDeviceAction
{
    public function __invoke(User $user, Device $device): Device
    {
        try {
            $this->validateBusinessRules($user, $device);

            return DB::transaction(function () use ($user, $device) {
                $this->updateDeviceStatus($device);
                $this->logSuccess($user, $device);

                return $device;
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
        DeviceValidator::statusMustBePending($device);
    }

    private function updateDeviceStatus(Device $device): void
    {
        $device->update(['validation_status' => DeviceValidationStatus::REJECTED]);
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info('Device successfully invalidated.', [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, Device $device): never
    {
        throw new InvalidateDeviceFailedException(
            previous: $e,
            context: [
                'user_id' => $user->id,
                'device_id' => $device->id,
                'validation_status' => $device->validation_status,
            ]
        );
    }
}
