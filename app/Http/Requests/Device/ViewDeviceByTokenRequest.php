<?php

namespace App\Http\Requests\Device;


use App\Models\DeviceSharingToken;
use App\Rules\ExpiredDeviceSharingTokenRule;
use Illuminate\Foundation\Http\FormRequest;

class ViewDeviceByTokenRequest extends FormRequest
{
    /**
     * Validates the 'token' field and returns an instance of DeviceSharingToken.
     */
    public function deviceSharingToken(): DeviceSharingToken
    {
        return DeviceSharingToken::with([
            'device.user',
            'device.deviceModel',
            'device.attributeValidationLogs',
            'device.transfers' => fn ($q) => $q->acceptedAndOrdered(),
        ])->where('token', $this->token)->firstOrFail();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'token' => [
                'bail',
                'required',
                'alpha_num',
                'size:8',
                new ExpiredDeviceSharingTokenRule,
            ],
        ];
    }
}
