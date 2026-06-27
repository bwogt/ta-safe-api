<?php

namespace App\Actions\PasswordReset\Check;

use App\Actions\PasswordReset\Block\PasswordResetBlockAction;
use App\Actions\PasswordReset\Fails\IncrementPasswordResetAttemptAction;
use App\Exceptions\Application\PasswordReset\CheckPasswordResetCodeFailedException;
use App\Exceptions\BusinessRules\PasswordReset\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetBlockedException;
use App\Guards\ResetPasswordGuard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class CheckPasswordResetCodeAction
{
    public function __invoke(string $email, string $code): void
    {
        try {
            Cache::lock("check_password_reset_code_lock:{$email}", 10)
                ->get(function () use ($email, $code) {
                    $this->enforceBusinessRules($email, $code);
                    $this->logSuccess($email);
                });
        } catch (PasswordResetBlockedException $e) {
            throw $e;
        } catch (PasswordResetAttemptExceededException $e) {
            (new PasswordResetBlockAction)($email);
            throw $e;
        } catch (InvalidPasswordResetCodeException $e) {
            (new IncrementPasswordResetAttemptAction)($email);
            throw $e;
        } catch (Throwable $e) {
            throw new CheckPasswordResetCodeFailedException(
                previous: $e,
                context: ['email' => $email]
            );
        }
    }

    private function enforceBusinessRules(string $email, string $code): void
    {
        ResetPasswordGuard::emailMustNotBeBlock($email);
        ResetPasswordGuard::attemptsMustNotBeExceeded($email);
        ResetPasswordGuard::codeMustBeValid($email, $code);
    }

    private function logSuccess(string $email): void
    {
        Log::info('User successfully checked password reset code.', ['email' => $email]);
    }
}
