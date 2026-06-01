<?php

namespace Tests\Unit\Actions\PasswordReset\Reset;

use App\Actions\PasswordReset\Block\PasswordResetBlockAction;
use App\Actions\PasswordReset\Reset\ResetPasswordAction;
use App\Exceptions\BusinessRules\PasswordReset\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetBlockedException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

final class ResetPasswordActionTest extends ResetPasswordActionTestSetUp
{
    public function test_should_update_the_user_password(): void
    {
        $dto = $this->makeDto();
        (new ResetPasswordAction)($dto);

        $this->user->refresh();
        $this->assertTrue(Hash::check($dto->password, $this->user->password));
    }

    public function test_should_clear_password_reset_code_state(): void
    {
        $dto = $this->makeDto();
        (new ResetPasswordAction)($dto);

        $this->assertFalse(Cache::has("password_reset_code:{$this->user->email}"));
        $this->assertFalse(Cache::has("password_reset_cooldown:{$this->user->email}"));
        $this->assertFalse(Cache::has("password_reset_attempts:{$this->user->email}"));
    }

    public function test_should_thrown_an_exception_when_the_code_is_invalid(): void
    {
        $this->expectException(InvalidPasswordResetCodeException::class);
        (new ResetPasswordAction)($this->makeDto(['code' => 'invalid_code']));
    }

    public function test_should_thrown_an_exception_when_the_email_is_blocked(): void
    {
        (new PasswordResetBlockAction)($this->user->email);

        $this->expectException(PasswordResetBlockedException::class);
        (new ResetPasswordAction)($this->makeDto());
    }

    public function test_should_throw_an_exception_when_the_attempts_is_exceeded(): void
    {
        $limit = (int) config('security.password_reset.max_attempts');
        Cache::put("password_reset_attempts:{$this->user->email}", $limit);

        $this->expectException(PasswordResetAttemptExceededException::class);
        (new ResetPasswordAction)($this->makeDto());
    }

    public function test_should_block_the_email_when_the_attempts_is_exceeded(): void
    {
        $limit = (int) config('security.password_reset.max_attempts');
        Cache::put("password_reset_attempts:{$this->user->email}", $limit);

        try {
            (new ResetPasswordAction)($this->makeDto());
            $this->fail('Expected InvalidPasswordResetCodeException was not thrown.');
        } catch (PasswordResetAttemptExceededException $e) {
            $this->assertTrue(Cache::has("password_reset_block:{$this->user->email}"));
        }
    }

    public function test_should_increment_the_password_reset_attempts(): void
    {
        $dto = $this->makeDto(['code' => 'invalid_code']);
        $key = "password_reset_attempts:{$this->user->email}";

        $this->assertEquals(0, Cache::get($key));

        try {
            (new ResetPasswordAction)($dto);
            $this->fail('Expected InvalidPasswordResetCodeException was not thrown.');
        } catch (InvalidPasswordResetCodeException $e) {
            $this->assertEquals(1, Cache::get($key));
        }
    }
}
