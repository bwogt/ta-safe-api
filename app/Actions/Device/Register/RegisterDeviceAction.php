<?php

namespace App\Actions\Device\Register;

use App\Dto\Device\RegisterDeviceDTO;
use App\Exceptions\Application\Device\RegisterDeviceFailedException;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterDeviceAction
{
    public function __invoke(User $user, RegisterDeviceDTO $data): Device
    {
        try {
            return DB::transaction(function () use ($user, $data) {
                $device = $this->registerDevice($user, $data);
                $this->registerInvoice($device, $data);
                $this->logSuccess($user, $device);

                return $device;
            });
        } catch (Throwable $e) {
            throw new RegisterDeviceFailedException(
                previous: $e,
                context: [
                    'user_id' => $user->id,
                    'device_model_id' => $data->deviceModelId,
                    'color' => $data->color,
                ]
            );
        }
    }
    private function registerDevice(User $user, RegisterDeviceDTO $data): Device
    {
        return Device::create([
            'user_id' => $user->id,
            'device_model_id' => $data->deviceModelId,
            'color' => $data->color,
            'imei_1' => $data->imei1,
            'imei_2' => $data->imei2,
        ]);
    }

    private function registerInvoice(Device $device, RegisterDeviceDTO $data): void
    {
        Invoice::create([
            'device_id' => $device->id,
            'access_key' => $data->accessKey,
        ]);
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info('Device successfully registered.', [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }
}
