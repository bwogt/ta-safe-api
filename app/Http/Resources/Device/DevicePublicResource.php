<?php

namespace App\Http\Resources\Device;

use App\Http\Resources\DeviceModel\DeviceModelResource;
use App\Http\Resources\DeviceTransfer\DeviceTransferBasicResource;
use App\Http\Resources\User\UserPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DevicePublicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'color' => $this->color,
            'validation_status' => $this->validation_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'owner' => new UserPublicResource($this->user),
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
