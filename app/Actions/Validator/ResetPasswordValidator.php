<?php

namespace App\Actions\Validator;

use App\Exceptions\BusinessRules\Auth\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\Auth\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\Auth\PasswordResetBlockedException;
use App\Exceptions\BusinessRules\Auth\PasswordResetCooldownException;
use Illuminate\Support\Facades\Cache;

final class ResetPasswordValidator
{
    public static function mustNotBeInCooldown(string $email): void
    {
        $isInCooldown = Cache::has("password_reset_cooldown:{$email}");

        throw_if($isInCooldown, new PasswordResetCooldownException(['email' => $email]));
    }

    public static function attemptsMustNotBeExceeded(string $email): void
    {
        $attempts = (int) Cache::get("password_reset_attempts:{$email}");
        $limit = (int) config('security.password_reset.max_attempts');

        throw_if($attempts >= $limit, new PasswordResetAttemptExceededException);
    }

    public static function codeMustBeValid(string $email, string $code): void
    {
        $cachedCode = Cache::get("password_reset_code:{$email}");

        if ($cachedCode) {
            $sameCode = hash_equals($cachedCode, $code);
            throw_unless($sameCode, new InvalidPasswordResetCodeException);
        } else {
            throw new InvalidPasswordResetCodeException;
        }
    }

    public static function emailMustNotBeBlock(string $email): void
    {
        $isBlocked = Cache::has("password_reset_block:{$email}");

        throw_if($isBlocked, new PasswordResetBlockedException(['email' => $email]));
    }
}
