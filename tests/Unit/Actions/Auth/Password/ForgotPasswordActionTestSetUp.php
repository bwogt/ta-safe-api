<?php

namespace Tests\Unit\Actions\Auth\Password;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Notification::fake();

        $this->user = UserFactory::new()->create();
    }

    protected function getResetCodeKey(string $email, bool $withPrefix = false): string
    {
        return $withPrefix
            ? config('cache.prefix') . ":password_reset_code:{$email}"
            : "password_reset_code:{$email}";
    }

    protected function getAttemptsKey(string $email, bool $withPrefix = false): string
    {
        return $withPrefix
            ? config('cache.prefix') . ":password_reset_attempts:{$email}"
            : "password_reset_attempts:{$email}";
    }

    protected function getCooldownKey(string $email, bool $withPrefix = false): string
    {
        return $withPrefix
            ? config('cache.prefix') . ":password_reset_cooldown:{$email}"
            : "password_reset_cooldown:{$email}";
    }
}
