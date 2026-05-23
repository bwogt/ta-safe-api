<?php

namespace Database\Seeders\Production;

use App\Models\Brand;
use App\Models\DeviceModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DeviceModelSeeder extends Seeder
{
    public function run(): void
    {
        $deviceModels = $this->loadDeviceModelsFile();

        foreach ( $deviceModels as $rawDeviceModel) {
            $brand = $this->brandByName($rawDeviceModel->brand);
            $this->createDeviceModel($brand, $rawDeviceModel);
        }
    }

    private function loadDeviceModelsFile(): mixed
    {
        $json = File::get(database_path('data/production/device-models.json'));

        return json_decode($json);
    }

    private function brandByName(string $name): Brand
    {
        return Brand::where('name', $name)->firstOrFail();
    }

    private function createDeviceModel(Brand $brand, $rawDeviceModel): void
    {
        DeviceModel::updateOrCreate([
            'brand_id' => $brand->id,
            'name' => $rawDeviceModel->name,
            'ram' => $rawDeviceModel->ram,
            'storage' => $rawDeviceModel->storage,
        ]);
    }
}
