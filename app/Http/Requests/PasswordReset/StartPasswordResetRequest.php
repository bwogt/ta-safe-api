<?php

namespace App\Http\Requests\PasswordReset;

use Illuminate\Foundation\Http\FormRequest;

class StartPasswordResetRequest extends FormRequest
{
    public function email(): string
    {
        return $this->input('email');
    }

    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'string',
                'email:filter',
            ],
        ];
    }
}
