<?php

namespace App\Actions\Validator;

use App\Exceptions\BusinessRules\Auth\PasswordResetCooldownException;
use Illuminate\Support\Facades\Cache;

final class ResetPasswordValidator
{
    public static function mustNotBeInCooldown(string $email): void
    {
        $isInCooldown = Cache::has("password_reset_cooldown:{$email}");

        throw_if($isInCooldown, new PasswordResetCooldownException(['email' => $email]));
    }
}
