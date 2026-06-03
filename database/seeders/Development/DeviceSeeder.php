<?php

namespace Database\Seeders\Development;

use App\Enums\Device\DeviceValidationStatus;
use App\Jobs\Device\ValidateDeviceRegistrationJob;
use App\Models\Device;
use App\Models\DeviceModel;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            $devices = $this->loadDevicesFile();

            foreach ($devices as $rawDevice) {
                $user = $this->userByCpf($rawDevice->user->cpf);
                $deviceModel = $this->deviceModelByName($rawDevice->model->name);
                $device = $this->createDevice($user, $deviceModel, $rawDevice);

                ValidateDeviceRegistrationJob::dispatchSync($device);
            }
        }
    }

    private function loadDevicesFile(): mixed
    {
        $json = File::get(database_path('data/development/devices.json'));

        return json_decode($json);
    }

    private function userByCpf(string $cpf): User
    {
        return User::where('cpf', $cpf)->firstOrFail();
    }

    private function deviceModelByName(string $name): DeviceModel
    {
        return DeviceModel::where('name', $name)->firstOrFail();
    }

    private function createDevice(User $user, DeviceModel $deviceModel, $rawDevice): Device
    {
        $device = Device::updateOrCreate([
            'user_id' => $user->id,
            'device_model_id' => $deviceModel->id,
            'validation_status' => DeviceValidationStatus::IN_ANALYSIS,
            'color' => $rawDevice->color,
            'imei_1' => $rawDevice->imei1,
            'imei_2' => $rawDevice->imei2,
        ]);

        $this->createInvoice($user, $device, $rawDevice);

        return $device;
    }

    private function createInvoice(User $user, Device $device, $rawDevice): void
    {
        Invoice::updateOrCreate([
            'device_id' => $device->id,
            'access_key' => $rawDevice->invoice->access_key,
            'consumer_cpf' => $user->cpf,
            'consumer_name' => $user->name,
            'product_description' => $rawDevice->invoice->product_description,
        ]);
    }
}
