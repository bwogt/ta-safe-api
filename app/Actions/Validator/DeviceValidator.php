<?php

namespace App\Actions\Validator;

use App\Enums\Device\DeviceValidationStatus;
use App\Exceptions\BusinessRules\Device\InvalidDeviceStateException;
use App\Exceptions\BusinessRules\Device\UserNotOwnerException;
use App\Models\Device;
use App\Models\User;

class DeviceValidator
{
    public static function mustBeOwner(User $user, Device $device): void
    {
        $isOwner = $user->id === $device->user_id;

        throw_unless($isOwner, new UserNotOwnerException([
            'device_id' => $device->id,
            'user_id' => $user->id,
        ]));
    }

    public static function statusMustBeRejected(Device $device): void
    {
        $isRejected = $device->validation_status->isRejected();

        throw_unless($isRejected, new InvalidDeviceStateException([
            'device_id' => $device->id,
            'current_status' => $device->validation_status,
            'expected_status' => DeviceValidationStatus::REJECTED,
        ]));
    }

    public static function statusMustBeValidated(Device $device): void
    {
        $isValidate = $device->validation_status->isValidated();

        throw_unless($isValidate, new InvalidDeviceStateException([
            'device_id' => $device->id,
            'current_status' => $device->validation_status,
            'expected_status' => DeviceValidationStatus::VALIDATED,
        ]));
    }

    public static function statusMustBePending(Device $device): void
    {
        $isPending = $device->validation_status->isPending();

        throw_unless($isPending, new InvalidDeviceStateException([
            'device_id' => $device->id,
            'current_status' => $device->validation_status,
            'expected_status' => DeviceValidationStatus::PENDING,
        ]));
    }

    public static function statusMustBeInAnalysis(Device $device): void
    {
        $isInAnalysis = $device->validation_status->isInAnalysis();

        throw_unless($isInAnalysis, new InvalidDeviceStateException([
            'device_id' => $device->id,
            'current_status' => $device->validation_status,
            'expected_status' => DeviceValidationStatus::IN_ANALYSIS,
        ]));
    }
}
