<?php

namespace Tests\Unit\Actions\Auth\Login;

use App\Actions\Auth\Login\LoginAction;
use App\Dto\Auth\CredentialsDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class LoginActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected LoginAction $action;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionSetUp();
        $this->userSetUp();
    }

    private function actionSetUp(): void
    {
        $this->action = new LoginAction;
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    protected function credentials($override = []): CredentialsDTO
    {
        return new CredentialsDTO(
            email: $override['email'] ?? $this->user->email,
            password: $override['password'] ?? 'password',
        );
    }
}
