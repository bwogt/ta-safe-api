<?php

namespace App\Dto\PasswordReset;

final class ResetPasswordDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $email,
        public readonly string $password
    ) {}
}
