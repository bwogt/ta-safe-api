<?php

namespace App\Http\Requests\PasswordReset;

use App\Dto\PasswordReset\ResetPasswordDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function toDto(): ResetPasswordDTO
    {
        return new ResetPasswordDTO(
            code: $this->input('code'),
            email: $this->input('email'),
            password: $this->input('password'),
        );
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'digits:6'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', Password::defaults()],
        ];
    }
}
