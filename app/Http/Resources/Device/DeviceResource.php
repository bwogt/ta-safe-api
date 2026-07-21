<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\DeviceModel\DeviceModelResource;
use App\Http\Resources\DeviceTransfer\DeviceTransferBasicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeviceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'color' => $this->color,
            'imei_1' => $this->imei_1,
            'imei_2' => $this->imei_2,
            'access_key' => $this->invoice->access_key,
            'validation_status' => $this->validation_status,
            'share_code' => DeviceShareCodeResource::make($this->activeShareCode()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'model' => new DeviceModelResource($this->deviceModel),
            'validated_attributes' => $this->validatedAttributes(),
            'transfers' => DeviceTransferBasicResource::collection($this->transfers),
        ];
    }

    private function validatedAttributes(): ?array
    {
        if ($this->attributeValidationLogs->isEmpty()) {
            return null;
        }

        return $this->attributeValidationLogs
            ->mapWithKeys(fn ($log) => [
                $log->attribute_label => $log->validated,
            ])
            ->toArray();
    }
}
