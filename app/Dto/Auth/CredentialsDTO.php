<?php

namespace App\Dto\Auth;

final class CredentialsDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
