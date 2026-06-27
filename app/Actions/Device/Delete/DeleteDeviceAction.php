<?php

namespace App\Actions\Device\Delete;

use App\Exceptions\Application\Device\DeleteDeviceFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Guards\DeviceGuard;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class DeleteDeviceAction
{
    public function __invoke(User $user, Device $device): bool
    {
        try {
            $this->enforceBusinessRules($user, $device);

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

    private function enforceBusinessRules(User $user, Device $device): void
    {
        DeviceGuard::mustBeOwner($user, $device);
        DeviceGuard::statusMustBeRejected($device);
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
