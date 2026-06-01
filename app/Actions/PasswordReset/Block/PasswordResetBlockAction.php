<?php

namespace App\Actions\PasswordReset\Block;

use App\Exceptions\Application\PasswordReset\PasswordResetBlockFailedException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class PasswordResetBlockAction
{
    public function __invoke(string $email): void
    {
        try {
            $added = $this->addPasswordResetBlock($email);

            if ($added) {
                $this->revokePasswordResetCode($email);
                $this->logSuccess($email);
            }
        } catch (Throwable $e) {
            throw new PasswordResetBlockFailedException(
                previous: $e,
                context: ['email' => $email]
            );
        }
    }

    private function addPasswordResetBlock(string $email): bool
    {
        $ttl = (int) config('security.password_reset.block');

        return Cache::add(
            key: "password_reset_block:{$email}",
            value: true,
            ttl: now()->addSeconds($ttl)
        );
    }

    private function revokePasswordResetCode(string $email): void
    {
        Cache::forget("password_reset_code:{$email}");
        Cache::forget("password_reset_attempts:{$email}");
        Cache::forget("password_reset_cooldown:{$email}");
    }

    private function logSuccess(string $email): void
    {
        Log::info('Temporary password reset block successfully added.', ['email' => $email]);
    }
}
