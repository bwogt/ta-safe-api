<?php

namespace Tests\Feature\Controllers\UserController\Update;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Asserts\AccessAsserts;
use Tests\TestCase;

abstract class UpdateUserTestSetUp extends TestCase
{
    use AccessAsserts;
    use RefreshDatabase;

    protected User $user;
    protected User $anotherUser;

    protected function setUp(): void
    {
        parent::SetUp();
        $this->userSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
        $this->anotherUser = UserFactory::new()->create();
    }

    protected function route(): string
    {
        return route('api.user.update', $this->user);
    }

    protected function data(array $overrides = []): array
    {
        return array_merge([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->cellPhoneNumber(),
        ], $overrides);
    }
}
