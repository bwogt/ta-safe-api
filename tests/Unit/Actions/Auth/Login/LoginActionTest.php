<?php

namespace Tests\Unit\Actions\Auth\Login;

use App\Actions\Auth\Login\LoginAction;
use App\Dto\Auth\LoginDTO;
use App\Exceptions\Application\Auth\LoginFailedException;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoginActionTest extends LoginActionTestSetUp
{
    public function should_authenticate_user_with_valid_credentials(): void
    {
        $login = (new LoginAction)($this->user, $this->credentials());

        $this->assertInstanceOf(LoginDTO::class, $login);
    }

    public function should_throw_invalid_credentials_exception_when_email_is_invalid(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $credentials = $this->credentials([
            'email' => fake()->unique()->safeEmail(),
        ]);

        (new LoginAction)($this->user, $credentials);
    }

    public function should_throw_invalid_credentials_exception_when_password_is_invalid(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $credentials = $this->credentials([
            'password' => 'wrong-password',
        ]);

        (new LoginAction)($this->user, $credentials);
    }

    public function should_throw_invalid_credentials_exception_when_credentials_are_invalid(): void
    {
        $this->expectException(LoginFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        (new LoginAction)($this->user, $this->credentials());
    }
}
