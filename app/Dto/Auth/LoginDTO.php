<?php

namespace App\Dto\Auth;

use App\Models\User;

final class LoginDTO
{
    public function __construct(
        public readonly User $user,
        public readonly string $token
    ) {}
}
