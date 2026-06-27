<?php

namespace App\Actions\PasswordReset\Reset;

use App\Actions\PasswordReset\Block\PasswordResetBlockAction;
use App\Actions\PasswordReset\Fails\IncrementPasswordResetAttemptAction;
use App\Dto\PasswordReset\ResetPasswordDTO;
use App\Exceptions\Application\PasswordReset\ResetPasswordFailedException;
use App\Exceptions\BusinessRules\PasswordReset\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetBlockedException;
use App\Guards\ResetPasswordGuard;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ResetPasswordAction
{
    public function __invoke(ResetPasswordDTO $dto): void
    {
        try {
            Cache::lock("reset_password_lock:{$dto->email}", 10)
                ->get(function () use ($dto) {
                    $this->enforceBusinessRules($dto);

                    DB::transaction(function () use ($dto) {
                        $user = $this->userByEmail($dto->email);

                        $this->updateUserPassword($user, $dto->password);
                        $this->revokeAllTokens($user);
                        $this->clearPasswordResetState($dto->email);
                        $this->logSuccess($user);
                    });
                });
        } catch (PasswordResetBlockedException $e) {
            throw $e;
        } catch (PasswordResetAttemptExceededException $e) {
            (new PasswordResetBlockAction)($dto->email);
            throw $e;
        } catch (InvalidPasswordResetCodeException $e) {
            (new IncrementPasswordResetAttemptAction)($dto->email);
            throw $e;
        } catch (Throwable $e) {
            throw new ResetPasswordFailedException(
                previous: $e,
                context: ['email' => $dto->email]
            );
        }
    }

    private function enforceBusinessRules(ResetPasswordDTO $dto): void
    {
        ResetPasswordGuard::emailMustNotBeBlock($dto->email);
        ResetPasswordGuard::attemptsMustNotBeExceeded($dto->email);
        ResetPasswordGuard::codeMustBeValid($dto->email, $dto->code);
    }

    private function userByEmail(string $email): User
    {
        return User::whereEmail($email)->firstOrFail();
    }

    private function updateUserPassword(User $user, string $password): void
    {
        $user->update(['password' => Hash::make($password)]);
    }

    private function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    private function clearPasswordResetState(string $email): void
    {
        Cache::forget("password_reset_code:{$email}");
        Cache::forget("password_reset_cooldown:{$email}");
        Cache::forget("password_reset_attempts:{$email}");
    }

    private function logSuccess(User $user): void
    {
        Log::info('User successfully reset password.', ['user_id' => $user->id]);
    }
}
