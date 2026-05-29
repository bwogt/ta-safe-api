<?php

namespace Tests\Unit\Actions\PasswordReset\Start;

use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Notifications\Auth\ForgotPasswordNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

final class StartPasswordResetActionTest extends StartPasswordResetActionTestSetUp
{
    public function test_should_store_password_reset_code(): void
    {
        $code = (new StartPasswordResetAction)($this->user->email);
        $key = $this->getPasswordResetCacheKey('code');

        $this->assertTrue(Cache::has($key));
        $hashCode = hash_hmac('sha256', $code, config('app.key'));

        $this->assertSame($hashCode, Cache::get($key));
    }

    public function test_should_use_configured_password_reset_code_ttl(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('code', true);
        $ttl = config('security.password_reset.ttl');

        $this->assertLessThanOrEqual($ttl, Redis::ttl($key));
    }

    public function test_should_store_password_reset_cooldown(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('cooldown');
        $this->assertTrue(Cache::has($key));
    }

    public function test_should_use_configured_password_reset_cooldown_ttl(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('cooldown', true);
        $ttl = config('security.password_reset.cooldown');

        $this->assertLessThanOrEqual($ttl, Redis::ttl($key));
    }

    public function test_should_not_store_new_password_reset_code_if_cooldown_is_active(): void
    {
        (new StartPasswordResetAction)($this->user->email);

        $key = $this->getPasswordResetCacheKey('code');
        $code = Cache::get($key);

        (new StartPasswordResetAction)($this->user->email);
        $newCode = Cache::get($key);

        $this->assertEquals($code, $newCode);
    }

    public function test_should_store_password_reset_attempts(): void
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

    public function test_should_use_configured_password_reset_code_ttl_for_attempts(): void
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
}
