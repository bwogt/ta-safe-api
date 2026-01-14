<?php

namespace App\Actions\Validator;

use App\Dto\Auth\CredentialsDTO;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthValidator
{
    public function __construct(
        private readonly ?User $user,
        private readonly CredentialsDTO $data,
    ) {}

    public static function for(?User $user, CredentialsDTO $data): self
    {
        return new self($user, $data);
    }

    public function credentialsMustBeValid(): self
    {
        $emailMatch = $this->data->email == $this->user?->email;
        $passwordMatch = Hash::check($this->data->password, $this->user?->password);

        throw_unless($emailMatch && $passwordMatch, new InvalidCredentialsException);

        return $this;
    }
}
