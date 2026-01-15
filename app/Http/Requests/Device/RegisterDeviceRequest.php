<?php

namespace App\Http\Requests\Device;

use App\Dto\Device\RegisterDeviceDTO;
use App\Http\Requests\ApiFormRequest;

class RegisterDeviceRequest extends ApiFormRequest
{
    public function toDto(): RegisterDeviceDTO
    {
        return new RegisterDeviceDTO(
            deviceModelId: $this->input('device_model_id'),
            accessKey: $this->input('access_key'),
            color: $this->input('color'),
            imei1: $this->input('imei_1'),
            imei2: $this->input('imei_2'),
        );
    }

    public function rules(): array
    {
        return [
            'device_model_id' => ['bail', 'required', 'integer', 'exists:device_models,id'],
            'access_key' => ['bail', 'required', 'digits:44', 'unique:invoices,access_key'],
            'color' => ['bail', 'required', 'max:255'],
            'imei_1' => ['bail', 'required', 'digits:15', 'different:imei_2', 'unique:devices,imei_1', 'unique:devices,imei_2'],
            'imei_2' => ['bail', 'required', 'digits:15', 'unique:devices,imei_1', 'unique:devices,imei_2'],
        ];
    }
}
