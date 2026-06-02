<?php

namespace App\Actions\PasswordReset\Fails;

use App\Exceptions\BusinessRules\PasswordReset\PasswordResetAttemptExceededException;
use Illuminate\Support\Facades\Cache;

final class IncrementPasswordResetAttemptAction
{
    public function __invoke(string $email): void
    {
        $attempts = $this->incrementAttempts($email);
        $this->checkAttemptsLimit($attempts);
    }

    private function incrementAttempts(string $email): int
    {
        return Cache::increment("password_reset_attempts:{$email}");
    }

    private function checkAttemptsLimit($attempts): void
    {
        $maxAttempts = (int) config('security.password_reset.max_attempts');

        if ($attempts > $maxAttempts) {
            throw new PasswordResetAttemptExceededException;
        }
    }
}
