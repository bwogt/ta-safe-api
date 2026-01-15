<?php

namespace App\Http\Requests\User;

use App\Dto\User\UpdateUserDTO;
use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiFormRequest
{
    public function toDto(): UpdateUserDTO
    {
        return new UpdateUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            phone: $this->input('phone'),
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
            'phone' => [
                'bail',
                'required',
                'regex:/^[(]\d{2}[)]\s\d{5}-\d{4}$/',
                Rule::unique('users')
                    ->ignore($this->user()->id),
            ],
        ];
    }
}
