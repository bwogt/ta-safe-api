<?php

namespace App\Dto\Password;

final class PasswordResetDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $email,
        public readonly string $password
    ) {}
}
