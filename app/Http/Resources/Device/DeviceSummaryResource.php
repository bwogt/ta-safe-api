<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\DeviceModel\DeviceModelResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeviceSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'color' => $this->color,
            'validation_status' => $this->validation_status,
            'model' => new DeviceModelResource($this->deviceModel),
            'validated_attributes' => $this->validatedAttributes(),
            'updated_at' => $this->updated_at,
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
            ])->toArray();
    }
}
