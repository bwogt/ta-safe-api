<?php

namespace App\Dto\Auth;

final class RegisterUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $cpf,
        public readonly string $phone,
        public readonly string $password
    ) {}
}
