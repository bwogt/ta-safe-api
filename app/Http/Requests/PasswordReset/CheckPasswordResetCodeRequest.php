<?php

namespace App\Http\Requests\PasswordReset;

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
            'code' => [
                'required',
                'digits:6',
            ],
            'email' => [
                'bail',
                'required',
                'string',
                'email:filter',
            ],
        ];
    }
}
