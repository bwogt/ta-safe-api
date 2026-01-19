<?php

namespace App\Http\Requests\Device;

use App\Models\DeviceSharingToken;
use App\Rules\ExpiredDeviceSharingTokenRule;
use Illuminate\Foundation\Http\FormRequest;

class ViewDeviceByTokenRequest extends FormRequest
{
    public function deviceSharingToken(): DeviceSharingToken
    {
        return DeviceSharingToken::with([
            'device.user',
            'device.deviceModel',
            'device.attributeValidationLogs',
            'device.transfers' => fn ($q) => $q->acceptedAndOrdered(),
        ])->where('token', $this->token)->firstOrFail();
    }

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
