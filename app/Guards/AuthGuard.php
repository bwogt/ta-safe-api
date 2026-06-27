<?php

namespace App\Guards;

use App\Dto\Auth\CredentialsDTO;
use App\Exceptions\BusinessRules\Auth\EmailNotExistsException;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthGuard
{
    public static function credentialsMustBeValid(?User $user, CredentialsDTO $data): void
    {
        $emailMatch = $data->email === $user?->email;
        $passwordMatch = Hash::check($data->password, $user?->password);

        throw_unless($emailMatch && $passwordMatch, new InvalidCredentialsException([
            'email' => $data->email,
        ]));
    }

    public static function emailMustBeExists(string $email): void
    {
        $emailExists = User::where('email', $email)->exists();

        throw_unless($emailExists, new EmailNotExistsException(['email' => $email]));
    }
}
