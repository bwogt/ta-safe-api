<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    public function userByEmail(): User
    {
        $email = $this->input('email');

        return User::whereEmail($email)->firstOrFail();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'email:filter',
                'exists:users,email',
            ],
        ];
    }
}
