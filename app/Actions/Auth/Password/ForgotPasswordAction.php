<?php

namespace App\Actions\Auth\Password;

use App\Actions\Validator\AuthValidator;
use App\Actions\Validator\ResetPasswordValidator;
use App\Exceptions\Application\Auth\ForgotPasswordFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Exceptions\Helpers\BusinessRuleExceptionLogger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ForgotPasswordAction
{
    public function __invoke(string $email): ?string
    {
        try {
            AuthValidator::emailMustBeExists($email);

            return Cache::lock("forgot_password_lock:{$email}", 10)
                ->get(function () use ($email) {
                    ResetPasswordValidator::mustNotBeInCooldown($email);

                    $code = $this->generatePasswordResetCode();

                    $this->storePasswordResetCode($email, $code);
                    $this->initializeAttempts($email);
                    $this->initializeResetCooldown($email);
                    $this->logSuccess($email);

                    return $code;
                });
        } catch (BusinessRuleException $e) {
            (new BusinessRuleExceptionLogger)($e);

            return null;
        } catch (Throwable $e) {
            throw new ForgotPasswordFailedException(
                previous: $e,
                context: ['email' => $email]
            );
        }
    }

    private function generatePasswordResetCode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private function storePasswordResetCode(string $email, string $code): void
    {
        $ttl = (int) config('security.password_reset.ttl');

        Cache::put(
            key: "password_reset_code:{$email}",
            value: Hash::make($code),
            ttl: now()->addSeconds($ttl)
        );
    }

    private function initializeAttempts(string $email): void
    {
        $ttl = (int) config('security.password_reset.ttl');

        Cache::put(
            key: "password_reset_attempts:{$email}",
            value: 0,
            ttl: now()->addSeconds($ttl)
        );
    }

    private function initializeResetCooldown(string $email): void
    {
        $ttl = (int) config('security.password_reset.cooldown');

        Cache::put(
            key: "password_reset_cooldown:{$email}",
            value: true,
            ttl: now()->addSeconds($ttl)
        );
    }

    private function logSuccess(string $email): void
    {
        Log::info('User successfully requested password reset code.', ['email' => $email]);
    }
}
