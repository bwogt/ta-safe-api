<?php

namespace App\Actions\Validator;

use App\Dto\Auth\CredentialsDTO;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthValidator
{
    public static function credentialsMustBeValid(?User $user, CredentialsDTO $data): void
    {
        $emailMatch = $data->email === $user?->email;
        $passwordMatch = Hash::check($data->password, $user?->password);

        throw_unless($emailMatch && $passwordMatch, new InvalidCredentialsException);
    }
}
