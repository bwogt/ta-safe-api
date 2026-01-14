<?php

namespace App\Actions\Auth\Login;

use App\Actions\Validator\AuthValidator;
use App\Dto\Auth\CredentialsDTO;
use App\Dto\Auth\LoginDTO;
use App\Exceptions\Application\Auth\LoginFailsException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class LoginAction
{
    public function __invoke(CredentialsDTO $data): LoginDTO
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = $this->findUserByEmail($data);
                $this->validateCredentials($user, $data);

                $this->deleteAllTokens($user);
                $token = $this->createPersonalAccessToken($user);
                $this->logSuccess($user);

                return new LoginDTO($user, $token);
            });
        } catch (BusinessRuleException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new LoginFailsException(
                previous: $e,
                context: ['email' => $data->email]
            );
        }
    }

    private function findUserByEmail(CredentialsDTO $data): ?User
    {
        return User::where('email', $data->email)->first();
    }

    private function validateCredentials(?User $user, CredentialsDTO $data): void
    {
        AuthValidator::for($user, $data)->credentialsMustBeValid();
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
