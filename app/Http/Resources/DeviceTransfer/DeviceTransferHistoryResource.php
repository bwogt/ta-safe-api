<?php

namespace App\Http\Resources\DeviceTransfer;

use App\Http\Resources\User\UserPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DeviceTransferHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'source_user' => new UserPublicResource($this->sourceUser),
            'target_user' => new UserPublicResource($this->targetUser),
            'updated_at' => $this->updated_at,
        ];
    }
}
