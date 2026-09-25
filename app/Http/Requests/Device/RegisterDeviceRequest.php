<?php

namespace App\Http\Requests\Device;

use App\Dto\Device\RegisterDeviceDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterDeviceRequest extends FormRequest
{
    public function toDto(): RegisterDeviceDTO
    {
        return new RegisterDeviceDTO(
            deviceModelId: $this->input('device_model_id'),
            accessKey: $this->input('access_key'),
            color: $this->input('color'),
        );
    }

    public function rules(): array
    {
        return [
            'device_model_id' => [
                'bail',
                'required',
                'integer',
                'exists:device_models,id',
            ],
            'access_key' => [
                'bail',
                'required',
                'digits:44',
                'unique:invoices,access_key',
            ],
            'color' => [
                'bail',
                'required',
                'max:255',
            ]
        ];
    }
}
