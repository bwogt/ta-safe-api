<?php

namespace App\Actions\PasswordReset\Start;

use App\Actions\Validator\AuthValidator;
use App\Actions\Validator\ResetPasswordValidator;
use App\Exceptions\Application\PasswordReset\StartPasswordResetFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Exceptions\Helpers\BusinessRuleExceptionLogger;
use App\Models\User;
use App\Notifications\Auth\ForgotPasswordNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class StartPasswordResetAction
{
    public function __invoke(string $email): ?string
    {
        try {
            return Cache::lock("forgot_password_lock:{$email}", 10)
                ->get(function () use ($email) {
                    $this->validateBusinessRules($email);

                    $user = $this->userByEmail($email);
                    $code = $this->generatePasswordResetCode();

                    $this->storePasswordResetCode($email, $code);
                    $user->notify(new ForgotPasswordNotification($code));

                    $this->initializeResetCooldown($email);
                    $this->initializeResetAttempts($email);

                    $this->logSuccess($email);

                    return $code;
                });
        } catch (BusinessRuleException $e) {
            (new BusinessRuleExceptionLogger)($e);

            return null;
        } catch (Throwable $e) {
            throw new StartPasswordResetFailedException(
                previous: $e,
                context: ['email' => $email]
            );
        }
    }

    private function validateBusinessRules(string $email): void
    {
        AuthValidator::emailMustBeExists($email);
        ResetPasswordValidator::mustNotBeInCooldown($email);
        ResetPasswordValidator::emailMustNotBeBlock($email);
    }

    private function userByEmail(string $email): User
    {
        return User::whereEmail($email)->firstOrFail();
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
            value: hash_hmac('sha256', $code, config('app.key')),
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

    private function initializeResetAttempts(string $email): void
    {
        $ttl = (int) config('security.password_reset.ttl');

        Cache::add(
            key: "password_reset_attempts:{$email}",
            value: 0,
            ttl: now()->addSeconds($ttl)
        );
    }

    private function logSuccess(string $email): void
    {
        Log::info('User successfully requested password reset code.', ['email' => $email]);
    }
}
