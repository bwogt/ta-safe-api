<?php

namespace Tests\Unit\Actions\PasswordReset\Start;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StartPasswordResetActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected string $email;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Notification::fake();

        $this->userSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    protected function getPasswordResetCacheKey(string $scope, bool $withPrefix = false): string
    {
        $key = "password_reset_{$scope}:{$this->user->email}";

        return ! $withPrefix ? $key : config('cache.prefix') . ":{$key}";
    }
}
