<?php

namespace Tests\Unit\Actions\PasswordReset\Check;

use App\Actions\PasswordReset\Check\CheckPasswordResetCodeAction;
use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Exceptions\BusinessRules\PasswordReset\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetAttemptExceededException;
use App\Exceptions\BusinessRules\PasswordReset\PasswordResetBlockedException;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

final class CheckPasswordResetCodeActionTest extends TestCase
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

    public function test_should_throw_an_exception_when_the_password_reset_code_is_invalid(): void
    {
        $this->expectException(InvalidPasswordResetCodeException::class);
        (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
    }

    public function test_should_increment_the_password_reset_attempts_when_the_code_is_invalid(): void
    {
        $key = "password_reset_attempts:{$this->user->email}";
        $this->assertEquals(0, Cache::get($key));

        try {
            (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
            $this->fail('Expected InvalidPasswordResetCodeException was not thrown.');
        } catch (InvalidPasswordResetCodeException $e) {
            $this->assertEquals(1, Cache::get($key));
        }
    }

    public function test_should_throw_an_exception_when_the_user_is_blocked(): void
    {
        Cache::put("password_reset_block:{$this->user->email}", true);
        $code = Cache::get("password_reset_code:{$this->user->email}");

        $this->expectException(PasswordResetBlockedException::class);
        (new CheckPasswordResetCodeAction)($this->user->email, $code);
    }

    public function test_should_temporary_block_the_user_when_the_password_reset_attempts_are_exceeded(): void
    {
        $limit = (int) config('security.password_reset.max_attempts');
        $key = "password_reset_attempts:{$this->user->email}";
        Cache::put($key, $limit + 1);

        try {
            (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
            $this->fail('Expected PasswordResetAttemptExceededException was not thrown.');
        } catch (PasswordResetAttemptExceededException $e) {
            $this->assertTrue(Cache::has("password_reset_block:{$this->user->email}"));
        }
    }
}
