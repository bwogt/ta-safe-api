<?php

namespace Tests\Unit\Actions\PasswordReset\Block;

use App\Actions\PasswordReset\Block\PasswordResetBlockAction;
use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class PasswordResetBlockActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Notification::fake();

        $this->user = UserFactory::new()->create();
        (new StartPasswordResetAction)($this->user->email);
    }

    public function test_should_store_password_reset_block(): void
    {
        (new PasswordResetBlockAction)($this->user->email);
        $this->assertTrue(Cache::has("password_reset_block:{$this->user->email}"));
    }

    public function test_should_revoke_password_reset_code_state(): void
    {
        (new PasswordResetBlockAction)($this->user->email);

        $this->assertFalse(Cache::has("password_reset_code:{$this->user->email}"));
        $this->assertFalse(Cache::has("password_reset_attempts:{$this->user->email}"));
        $this->assertFalse(Cache::has("password_reset_cooldown:{$this->user->email}"));
    }

}
