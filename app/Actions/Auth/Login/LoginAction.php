<?php

namespace App\Actions\Auth\Login;

use App\Dto\Auth\CredentialsDTO;
use App\Dto\Auth\LoginDTO;
use App\Exceptions\Application\Auth\LoginFailedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Guards\AuthGuard;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class LoginAction
{
    public function __invoke(?User $user, CredentialsDTO $credentials): LoginDTO
    {
        try {
            return DB::transaction(function () use ($user, $credentials) {
                $this->enforceBusinessRules($user, $credentials);

                $this->deleteAllTokens($user);
                $token = $this->createPersonalAccessToken($user);
                $this->logSuccess($user);

                return new LoginDTO($user, $token);
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new LoginFailedException(
                previous: $e,
                context: ['email' => $credentials->email]
            );
        }
    }

    private function enforceBusinessRules(?User $user, CredentialsDTO $data): void
    {
        AuthGuard::credentialsMustBeValid($user, $data);
    }

    private function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    private function createPersonalAccessToken(User $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    private function logSuccess(User $user): void
    {
        Log::info('User successfully logged in.', ['user_id' => $user->id]);
    }
}
