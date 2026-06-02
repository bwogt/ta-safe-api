<?php

namespace Tests\Unit\Actions\PasswordReset\Start;

use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetBlockedException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetCooldownException;
use App\Notifications\Auth\ForgotPasswordNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

final class StartPasswordResetActionTest extends StartPasswordResetActionTestSetUp
{
    public function test_should_persist_password_reset_code_for_later_verification(): void
    {
        $code = (new StartPasswordResetAction)($this->user->email);
        $key = $this->getPasswordResetCacheKey('code');

        $this->assertTrue(Cache::has($key));
        $hashCode = hash_hmac('sha256', $code, config('app.key'));

        $this->assertSame($hashCode, Cache::get($key));
    }

    public function test_should_store_reset_code_with_configured_ttl(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('code', true);
        $ttl = config('security.password_reset.ttl');

        $this->assertLessThanOrEqual($ttl, Redis::ttl($key));
    }

    public function test_should_prevent_immediate_reset_resend(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('cooldown');
        $this->assertTrue(Cache::has($key));
    }

    public function test_should_store_cooldown_with_configured_ttl(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('cooldown', true);
        $ttl = config('security.password_reset.cooldown');

        $this->assertLessThanOrEqual($ttl, Redis::ttl($key));
    }

    public function test_should_persist_password_reset_attempts(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('attempts');
        $this->assertTrue(Cache::has($key));
    }

    public function test_should_initialize_password_reset_attempts_with_zero(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('attempts');
        $attempts = Cache::get($key);

        $this->assertEquals(0, $attempts);
    }

    public function test_should_store_attempts_with_same_ttl_as_reset_code(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('attempts', true);
        $ttl = config('security.password_reset.ttl');

        $this->assertLessThanOrEqual($ttl, Redis::ttl($key));
    }

    public function test_should_send_password_reset_notification(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        Notification::assertSentTo($this->user, ForgotPasswordNotification::class);
    }

    public function test_should_throw_an_exception_when_the_password_reset_cooldown_is_active(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $this->expectException(PasswordResetCooldownException::class);
        (new StartPasswordResetAction)($this->user->email);
    }

    public function test_should_throw_an_exception_when_the_email_is_blocked(): void
    {
        Cache::put("password_reset_block:{$this->user->email}", true);

        $this->expectException(PasswordResetBlockedException::class);
        (new StartPasswordResetAction)($this->user->email);
    }
}
