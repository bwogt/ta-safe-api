<?php

namespace App\Actions\Validator;

use App\Exceptions\BusinessRules\Device\ActiveShareCodeException;
use App\Exceptions\BusinessRules\Device\ShareCodeNotFoundException;
use App\Models\Device;
use Illuminate\Support\Facades\Redis;

final class DeviceShareValidator
{
    public static function mustNotHaveAnActiveCode(Device $device): void
    {
        $exists = (bool) Redis::exists("device:{$device->id}:active_share");

        throw_if($exists, new ActiveShareCodeException(['device_id' => $device->id]));
    }

    public static function codeMustBeActive(string $code): void
    {
        $exists = (bool) Redis::exists("device_share:code:{$code}");

        throw_unless($exists, new ShareCodeNotFoundException);
    }
}
