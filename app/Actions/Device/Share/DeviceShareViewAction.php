<?php

namespace App\Actions\Device\Share;

use App\Actions\Validator\DeviceShareValidator;
use App\Exceptions\Application\Device\DeviceShareViewException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Throwable;

final class DeviceShareViewAction
{
    public function __invoke(User $user, string $code): Device
    {
        try {
            $this->enforceBusinessRules($code);

            $device = $this->findByCode($code);
            $this->logSuccess($user, $device);

            return $device;
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new DeviceShareViewException(
                previous: $e,
                context: [
                    'user_id' => $user->id,
                    'code' => $code,
                ]
            );
        }
    }

    private function enforceBusinessRules(string $code): void
    {
        DeviceShareValidator::codeMustBeActive($code);
    }

    private function findByCode(string $code): Device
    {
        $deviceId = Redis::get("device_share:code:{$code}");

        return Device::findOrFail($deviceId);
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info(
            message: 'Device retrieved successfully via share code.',
            context: [
                'user_id' => $user->id,
                'device_id' => $device->id,
            ]
        );
    }
}
