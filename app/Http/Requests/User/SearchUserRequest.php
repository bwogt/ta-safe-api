<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    public function userByEmail(): User
    {
        $email = $this->input('email');

        return User::where('email', $email)->firstOrFail();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'email',
                'max:255',
                'exists:users,email',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.exists' => trans('validation.custom.email.exists'),
        ];
    }
}
