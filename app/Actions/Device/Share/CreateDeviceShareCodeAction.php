<?php

namespace App\Actions\Device\Share;

use App\Actions\Validator\DeviceShareValidator;
use App\Actions\Validator\DeviceValidator;
use App\Exceptions\Application\Device\CreateDeviceShareCodeException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use RuntimeException;
use Throwable;

final class CreateDeviceShareCodeAction
{
    private const MAX_GENERATION_ATTEMPTS = 5;

    public function __invoke(User $user, Device $device): string
    {
        try {
            return Cache::lock('device_share_lock:' . $device->id, 10)
                ->get(function () use ($user, $device) {
                    $this->enforceBusinessRules($user, $device);

                    $code = $this->generateAndStoreDeviceShareCode($device);
                    $this->logSuccess($user, $device);

                    return $code;
                });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new CreateDeviceShareCodeException(
                previous: $e,
                context: [
                    'user_id' => $user->id,
                    'device_id' => $device->id,
                ]
            );
        }

    }

    private function enforceBusinessRules(User $user, Device $device): void
    {
        DeviceValidator::mustBeOwner($user, $device);
        DeviceValidator::statusMustBeValidated($device);
        DeviceShareValidator::mustNotHaveAnActiveCode($device);
    }

    private function generateAndStoreDeviceShareCode(Device $device): string
    {
        $attempts = 0;

        do {
            $this->ensureAttemptsLimitNotExceeded(++$attempts);

            $code = (string) random_int(10000000, 99999999);
            $stored = $this->createDeviceShareCodeAtomically($device, $code);
        } while (! $stored);

        return $code;
    }

    private function ensureAttemptsLimitNotExceeded(int $attempts): void
    {
        if ($attempts > self::MAX_GENERATION_ATTEMPTS) {
            throw new RuntimeException(
                'Maximum attempts reached while generating device share code.'
            );
        }
    }

    private function createDeviceShareCodeAtomically(Device $device, string $code): bool
    {
        $result = Redis::eval(
            $this->script(),
            2,
            "device_share:code:{$code}",
            "device:{$device->id}:active_share",
            $device->id,
            $code,
            86400
        );

        return (bool) $result;
    }

    private function script(): string
    {
        return <<<'LUA'
            local code_key = KEYS[1]
            local active_share_key = KEYS[2]
            local device_id = ARGV[1]
            local code = ARGV[2]
            local ttl = ARGV[3]

            if redis.call('EXISTS', active_share_key) == 1 then
                return 0
            end

            if redis.call('EXISTS', code_key) == 1 then
                return 0
            end

            redis.call('SET', code_key, device_id, 'EX', ttl)
            redis.call('SET', active_share_key, code, 'EX', ttl)

            return 1
        LUA;
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info(
            message: 'The device sharing code was successfully created.',
            context: [
                'user_id' => $device->user_id,
                'device_id' => $device->id,
            ],
        );
    }
}
