<?php

namespace Tests\Unit\Actions\PasswordReset\Reset;

use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Dto\Password\PasswordResetDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $code;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Notification::fake();

        $this->userSetUp();
        $this->startPasswordResetSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function startPasswordResetSetUp(): void
    {
        $code = (new StartPasswordResetAction)($this->user->email);
        $this->code = $code;
    }

    protected function makeDto(array $overrides = []): PasswordResetDTO
    {
        return new PasswordResetDTO(
            code: $overrides['code'] ?? $this->code,
            email: $overrides['email'] ?? $this->user->email,
            password: $overrides['password'] ?? 'password2026',
        );
    }
}
