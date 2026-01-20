<?php

namespace App\Http\Requests\Device;

use App\Dto\DeviceTransfer\CreateDeviceTransferDTO;
use App\Models\Device;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateDeviceTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('accessAsOwner', $this->device);
    }

    public function toDto(Device $device): CreateDeviceTransferDTO
    {
        $targetUserId = $this->input('target_user_id');

        return new CreateDeviceTransferDTO(
            device: $device,
            targetUser: User::findOrFail($targetUserId),
        );
    }

    public function rules(): array
    {
        return [
            'target_user_id' => ['bail', 'required', 'integer', 'exists:users,id'],
        ];
    }
}
