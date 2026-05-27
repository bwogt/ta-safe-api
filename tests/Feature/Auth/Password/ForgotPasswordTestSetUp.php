<?php

namespace Tests\Feature\Auth\Password;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ForgotPasswordTestSetUp extends TestCase
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

    protected function route(): string
    {
        return route('api.auth.forgot-password');
    }
}
