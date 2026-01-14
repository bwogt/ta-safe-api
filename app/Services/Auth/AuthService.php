<?php

namespace App\Services\Auth;

use App\Actions\Auth\Login\LoginAction;
use App\Dto\Auth\LoginDto;

class AuthService
{
    /**
     * Logs the user in and returns a LoginDto containing the user
     * and the Personal Access Token.
     */
    public function login(string $email, string $password): LoginDto
    {
        return (new LoginAction($email, $password))->execute();
    }
}
