<?php

namespace Tests\Unit\Actions\Auth\Password;

use App\Actions\Auth\Password\ForgotPasswordAction;
use App\Notifications\Auth\ForgotPasswordNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

final class ForgotPasswordActionTest extends ForgotPasswordActionTestSetUp
{
    public function test_should_store_password_reset_code(): void
    {
        $code = (new ForgotPasswordAction)($this->user->email);

        $key = $this->getResetCodeKey($this->user->email);

        $this->assertTrue(Cache::has($key));
        $this->assertTrue(Hash::check($code, Cache::get($key)));
    }

    public function test_should_use_configured_password_reset_code_ttl(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getResetCodeKey($this->user->email, true);
        $ttl = Redis::ttl($key);

        $this->assertLessThanOrEqual(config('security.password_reset.ttl'), $ttl);
    }

    public function test_should_store_password_reset_attempts(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getAttemptsKey($this->user->email);
        $this->assertTrue(Cache::has($key));
    }

    public function test_should_initialize_password_reset_attempts_with_zero(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getAttemptsKey($this->user->email);
        $attempts = Cache::get($key);

        $this->assertEquals(0, $attempts);
    }

    public function test_should_use_configured_password_reset_code_ttl_for_attempts(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getAttemptsKey($this->user->email);
        $ttl = Redis::ttl($key);

        $this->assertLessThanOrEqual(config('security.password_reset.ttl'), $ttl);
    }

    public function test_should_store_password_reset_cooldown(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getCooldownKey($this->user->email);
        $this->assertTrue(Cache::has($key));
    }

    public function test_should_use_configured_password_reset_cooldown_ttl(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getCooldownKey($this->user->email, true);
        $ttl = Redis::ttl($key);

        $this->assertLessThanOrEqual(config('security.password_reset.cooldown'), $ttl);
    }

    public function test_should_not_initialize_password_reset_flow_for_invalid_email(): void
    {
        (new ForgotPasswordAction)('dont@email.com');

        $codeKey = $this->getResetCodeKey('dont@email.com');
        $attemptKey = $this->getAttemptsKey('dont@email.com');
        $cooldownKey = $this->getCooldownKey('dont@email.com');

        $this->assertFalse(Cache::has($codeKey));
        $this->assertFalse(Cache::has($attemptKey));
        $this->assertFalse(Cache::has($cooldownKey));
    }

    public function test_should_not_store_new_password_reset_code_if_cooldown_is_active(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        $key = $this->getResetCodeKey($this->user->email);
        $code = Cache::get($key);

        (new ForgotPasswordAction)($this->user->email);
        $newCode = Cache::get($key);

        $this->assertEquals($code, $newCode);
    }

    public function test_should_send_password_reset_notification(): void
    {
        (new ForgotPasswordAction)($this->user->email);

        Notification::assertSentTo($this->user, ForgotPasswordNotification::class);
    }
}
