<?php

namespace Tests\Unit\Actions\Auth\Register;

use App\Dto\Auth\LoginDTO;
use App\Exceptions\Application\Auth\RegisterUserFailedException;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserActionTest extends RegisterUserActionTestSetUp
{
    public function test_should_return_an_instance_of_login_dto_when_registration_is_successful(): void
    {
        $this->assertInstanceOf(LoginDTO::class, ($this->action)($this->data));
    }

    public function test_should_create_a_new_user_in_the_database(): void
    {
        $loginDto = ($this->action)($this->data);

        $this->assertDatabaseHas('users', [
            'id' => $loginDto->user->id,
            'name' => $this->data->name,
            'email' => $this->data->email,
            'cpf' => $this->data->cpf,
            'phone' => $this->data->phone,
        ]);
    }

    public function test_should_throw_register_user_failed_exception_on_failure(): void
    {
        $this->expectException(RegisterUserFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->data);
    }
}
