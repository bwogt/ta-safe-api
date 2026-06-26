<?php

namespace App\Http\Requests\Device;

use Illuminate\Foundation\Http\FormRequest;

final class DeviceShareViewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'digits:8'],
        ];
    }
}
