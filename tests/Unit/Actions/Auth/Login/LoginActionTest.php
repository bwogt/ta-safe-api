<?php

namespace Tests\Unit\Actions\Auth\Login;

use App\Dto\Auth\LoginDTO;
use App\Exceptions\Application\Auth\LoginFailsException;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LoginActionTest extends LoginActionTestSetUp
{
    public function test_should_return_an_instance_of_login_dto_when_registration_is_successful(): void
    {
        $this->assertInstanceOf(LoginDTO::class, ($this->action)($this->credentials()));
    }

    public function test_should_throw_an_exception_when_the_email_is_incorrect(): void
    {
        $this->expectException(InvalidCredentialsException::class);
        $anotherEmail = fake()->unique()->safeEmail();

        ($this->action)($this->credentials(['email' => $anotherEmail]));
    }

    public function test_should_throw_an_exception_when_the_password_is_incorrect(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        ($this->action)($this->credentials(['password' => 'wrong-password']));
    }

    public function test_should_throw_login_failed_exception_on_failure(): void
    {
        $this->expectException(LoginFailsException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->credentials());
    }
}
