<?php

namespace App\Http\Requests\Auth;

use App\Dto\Auth\CredentialsDTO;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function userByEmail(): ?User
    {
        return User::whereEmail($this->input('email'))->first();
    }

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
            'email' => ['required', 'string', 'email:filter'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }
}
