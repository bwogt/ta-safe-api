<?php

namespace App\Http\Resources\DeviceTransfer;

use App\Http\Resources\Device\DeviceSummaryResource;
use App\Http\Resources\User\UserPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceTransferResource extends JsonResource
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
            'type' => self::defineType(),
            'status' => $this->status,
            'source_user' => new UserPublicResource($this->sourceUser),
            'target_user' => new UserPublicResource($this->targetUser),
            'device' => new DeviceSummaryResource($this->device),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function defineType(): string
    {
        return request()->user()->id === $this->sourceUser->id ? 'sent' : 'received';
    }
}
