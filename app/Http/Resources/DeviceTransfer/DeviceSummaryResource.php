<?php

namespace App\Http\Resources\DeviceTransfer;

use App\Http\Resources\DeviceModel\DeviceModelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

final class DeviceSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'color' => $this->color,
            'validation_status' => $this->validation_status,
            'share_code' => Redis::get("device:{$this->id}:active_share"),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'device_model' => new DeviceModelResource($this->deviceModel),
        ];
    }
}
