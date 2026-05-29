<?php

namespace App\Actions\Auth\Reset\Check;

use App\Actions\Auth\Reset\Block\PasswordResetBlockAction;
use App\Actions\Validator\ResetPasswordValidator;
use App\Exceptions\Application\Auth\CheckPasswordResetCodeFailedException;
use App\Exceptions\BusinessRules\Auth\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\Auth\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\Auth\PasswordResetBlockedException;
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
                    $this->validateBusinessRules($email, $code);
                    $this->logSuccess($email);
                });
        } catch (PasswordResetBlockedException $e) {
            throw $e;
        } catch (PasswordResetAttemptExceededException $e) {
            (new PasswordResetBlockAction)($email);
            throw $e;
        } catch (InvalidPasswordResetCodeException $e) {
            $this->incrementAttempts($email);
            throw $e;
        } catch (Throwable $e) {
            throw new CheckPasswordResetCodeFailedException(
                previous: $e,
                context: ['email' => $email]
            );
        }
    }

    private function validateBusinessRules(string $email, string $code): void
    {
        ResetPasswordValidator::emailMustNotBeBlock($email);
        ResetPasswordValidator::attemptsMustNotBeExceeded($email);
        ResetPasswordValidator::codeMustBeValid($email, $code);
    }

    private function incrementAttempts(string $email): void
    {
        Cache::increment("password_reset_attempts:{$email}");
    }

    private function logSuccess(string $email): void
    {
        Log::info('User successfully checked password reset code.', ['email' => $email]);
    }
}
