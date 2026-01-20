<?php

namespace App\Actions\Auth\Register;

use App\Dto\Auth\LoginDTO;
use App\Dto\Auth\RegisterUserDTO;
use App\Exceptions\Application\Auth\RegisterUserFailedException;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegisterUserAction
{
    public function __invoke(RegisterUserDTO $data): LoginDTO
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = $this->registerUser($data);
                $token = $this->createPersonalAccessToken($user);
                $this->logSuccess($user);

                return new LoginDTO($user, $token);
            });
        } catch (Throwable $e) {
            throw new RegisterUserFailedException(
                previous: $e,
                context: [
                    'user_name' => $data->name,
                    'user_email' => $data->email,
                ]
            );
        }
    }

    private function registerUser(RegisterUserDTO $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'cpf' => $data->cpf,
            'phone' => $data->phone,
            'password' => $data->password,
        ]);
    }

    private function createPersonalAccessToken(User $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    private function logSuccess(User $user): void
    {
        Log::info('User successfully registered.', ['user_id' => $user->id]);
    }
}
