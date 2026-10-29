<?php

namespace App\Http\Resources\DeviceTransfer;

use App\Http\Resources\DeviceModel\DeviceModelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeviceTransferSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => self::defineType(),
            'status' => $this->status,
            'device_model' => new DeviceModelResource($this->device->deviceModel),
            'created_at' => $this->created_at,
        ];
    }

    private function defineType(): string
    {
        return request()->user()->id === $this->sourceUser->id ? 'sent' : 'received';
    }
}
