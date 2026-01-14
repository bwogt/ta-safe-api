<?php

namespace App\Http\Requests\Auth;

use App\Dto\Auth\CredentialsDTO;
use App\Http\Requests\ApiFormRequest;

class LoginRequest extends ApiFormRequest
{
    public function toDto(): CredentialsDTO
    {
        return new CredentialsDTO(
            email: $this->input('email'),
            password: $this->input('password')
        );
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
