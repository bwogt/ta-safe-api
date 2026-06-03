<?php

namespace App\Http\Requests\User;

use App\Dto\User\UpdateUserDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function toDto(): UpdateUserDTO
    {
        return new UpdateUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
        );
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'bail',
                'required',
                'email',
                'max:255',
                Rule::unique('users')
                    ->ignore($this->user()->id),
            ],
        ];
    }
}
