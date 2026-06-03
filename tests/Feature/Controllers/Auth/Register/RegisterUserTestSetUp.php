<?php

namespace Tests\Feature\Controllers\Auth\Register;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class RegisterUserTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();
    }

    protected function route(): string
    {
        return route('api.auth.register');
    }

    protected function data(array $overrides = []): array
    {
        return array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf' => fake()->unique()->cpf(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }
}
