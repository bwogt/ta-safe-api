<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckPasswordResetCodeRequest extends FormRequest
{
    public function email(): string
    {
        return $this->input('email');
    }

    public function code(): string
    {
        return $this->input('code');
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'digits:6'],
        ];
    }
}
