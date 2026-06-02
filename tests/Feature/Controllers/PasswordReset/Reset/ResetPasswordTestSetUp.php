<?php

namespace Tests\Feature\Controllers\PasswordReset\Reset;

use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    private string $code;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Notification::fake();

        $this->userSetUp();
        $this->codeSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function codeSetUp(): void
    {
        $this->code = (new StartPasswordResetAction)($this->user->email);
    }

    protected function route(): string
    {
        return route('api.password-reset.reset');
    }

    protected function data(array $override = []): array
    {
        return array_merge([
            'code' => $this->code,
            'email' => $this->user->email,
            'password' => 'password2026',
        ], $override);
    }
}
