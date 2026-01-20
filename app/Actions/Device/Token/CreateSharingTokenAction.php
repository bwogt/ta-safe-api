<?php

namespace App\Actions\Device\Token;

use App\Actions\Validator\DeviceValidator;
use App\Exceptions\Application\Device\CreateSharingTokenFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\Device;
use App\Models\DeviceSharingToken;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class CreateSharingTokenAction
{
    public function __invoke(User $user, Device $device): DeviceSharingToken
    {
        try {
            $this->validateBusinessRules($user, $device);

            return DB::transaction(function () use ($user, $device) {
                $this->deleteOldToken($device);
                $token = $this->createSharingToken($device);
                $this->logSuccess($user, $device);

                return $token;
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            $this->handleFailure($e, $user, $device);
        }
    }

    private function validateBusinessRules(User $user, Device $device): void
    {
        DeviceValidator::mustBeOwner($user, $device);
        DeviceValidator::statusMustBeValidated($device);
    }

    private function deleteOldToken(Device $device): void
    {
        $device->sharingToken()->delete();
    }

    private function createSharingToken(Device $device): DeviceSharingToken
    {
        return $device->sharingToken()->create([
            'token' => $this->generateUniqueToken(),
            'expires_at' => now()->addDay(),
        ]);
    }

    private function generateUniqueToken(int $depth = 0): string
    {
        throw_if($depth > 8, new RuntimeException(
            'Maximum depth reached in random token generation.'
        ));

        $token = strtoupper(bin2hex(random_bytes(4)));
        $isUnique = DeviceSharingToken::where('token', $token)->doesntExist();

        return $isUnique ? $token : $this->generateUniqueToken($depth + 1);
    }

    private function logSuccess(User $user, Device $device): void
    {
        Log::info('Device sharing token successfully created.', [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);
    }

    private function handleFailure(Throwable $e, User $user, Device $device): never
    {
        throw new CreateSharingTokenFailedException(
            previous: $e,
            context: [
                'user_id' => $user->id,
                'device_id' => $device->id,
            ]
        );
    }
}
