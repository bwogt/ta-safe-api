<?php

namespace App\Http\Requests\Auth;

use App\Dto\Auth\RegisterUserDTO;
use App\Rules\CpfRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterUserRequest extends FormRequest
{
    public function toDto(): RegisterUserDTO
    {
        return new RegisterUserDTO(
            name: $this->input('name'),
            email: $this->input('email'),
            cpf: $this->input('cpf'),
            phone: $this->input('phone'),
            password: $this->input('password'),
        );
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'email', 'unique:users,email'],
            'cpf' => ['bail', 'required', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', new CpfRule, 'unique:users'],
            'phone' => ['bail', 'required', 'regex:/^[(]\d{2}[)]\s\d{5}-\d{4}$/', 'unique:users,phone'],
            'password' => ['required', 'confirmed', 'max:255', Rules\Password::defaults()],
        ];
    }
}
