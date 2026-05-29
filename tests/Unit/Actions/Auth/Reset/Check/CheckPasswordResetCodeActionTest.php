<?php

namespace Tests\Unit\Actions\Auth\Reset\Check;

use App\Actions\Auth\Password\ForgotPasswordAction;
use App\Actions\Auth\Reset\Check\CheckPasswordResetCodeAction;
use App\Exceptions\BusinessRules\Auth\InvalidPasswordResetCodeException;
use App\Exceptions\BusinessRules\Auth\PasswordResetBlockedException;
use App\Exceptions\BusinessRules\BusinessRuleException;
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
        (new ForgotPasswordAction)($this->user->email);
    }

    public function test_checks_password_reset_code_successfully(): void
    {
        $code = Cache::get("password_reset_code:{$this->user->email}");
        (new CheckPasswordResetCodeAction)($this->user->email, $code);

        $this->assertTrue(true);
    }

    public function test_should_throw_an_exception_when_the_password_reset_code_is_invalid(): void
    {
        $this->expectException(InvalidPasswordResetCodeException::class);
        (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
    }

    public function test_should_increment_the_password_reset_attempts_when_the_code_is_invalid(): void
    {
        $attempts = Cache::get("password_reset_attempts:{$this->user->email}");
        $this->assertEquals(0, $attempts);

        try {
            (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
        } catch (InvalidPasswordResetCodeException $e) {
        }

        $attempts = Cache::get("password_reset_attempts:{$this->user->email}");
        $this->assertEquals(1, $attempts);
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
        Cache::put("password_reset_attempts:{$this->user->email}", $limit);

        try {
            (new CheckPasswordResetCodeAction)($this->user->email, 'invalid_code');
        } catch (BusinessRuleException $e) {
        }

        $this->assertTrue(Cache::get("password_reset_block:{$this->user->email}"));
    }
}
