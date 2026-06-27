<?php

namespace App\Http\Controllers\DeviceModel;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceModel\DeviceModelResource;
use App\Models\Brand;
use App\Models\DeviceModel;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeviceModelController extends Controller
{
    public function deviceModelsByBrand(Brand $brand): JsonResource
    {
        $deviceModels = DeviceModel::where(
            'brand_id', $brand->id
        )->get();

        return DeviceModelResource::collection($deviceModels);
    }
}
