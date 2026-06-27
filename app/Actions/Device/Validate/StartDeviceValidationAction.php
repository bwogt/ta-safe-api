<?php

namespace App\Actions\Device\Validate;

use App\Dto\Device\DeviceInvoiceDTO;
use App\Enums\Device\DeviceValidationStatus;
use App\Exceptions\Application\Device\StartDeviceValidationFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Guards\DeviceGuard;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class StartDeviceValidationAction
{
    public function __invoke(User $user, Device $device, DeviceInvoiceDTO $data): Device
    {
        try {
            $this->enforceBusinessRules($user, $device);

            return DB::transaction(function () use ($user, $device, $data) {
                $this->updateDeviceStatus($device);
                $this->updateDeviceInvoice($device, $data);
                $this->logSuccess($user, $device);

                return $device;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new StartDeviceValidationFailedException(
                previous: $e,
                context: [
                    'user_id' => $user->id,
                    'device_id' => $device->id,
                ]
            );
        }
    }

    private function enforceBusinessRules(User $user, Device $device): void
    {
        DeviceGuard::mustBeOwner($user, $device);
        DeviceGuard::statusMustBePending($device);
    }

    private function updateDeviceStatus(Device $device): void
    {
        $device->update(['validation_status' => DeviceValidationStatus::IN_ANALYSIS]);
    }

    private function updateDeviceInvoice(Device $device, DeviceInvoiceDTO $data): void
    {
        $device->invoice->update([
            'consumer_cpf' => $data->cpf,
            'consumer_name' => $data->name,
            'product_description' => $data->products,
        ]);
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info('Device validation successfully started.', [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }
}
