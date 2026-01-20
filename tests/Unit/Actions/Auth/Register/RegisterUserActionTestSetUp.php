<?php

namespace Tests\Unit\Actions\Auth\Register;

use App\Actions\Auth\Register\RegisterUserAction;
use App\Dto\Auth\RegisterUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class RegisterUserActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected RegisterUserAction $action;
    protected RegisterUserDTO $data;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionSetUp();
        $this->dataSetUp();
    }

    private function actionSetUp(): void
    {
        $this->action = new RegisterUserAction;
    }

    private function dataSetUp(): void
    {
        $this->data = new RegisterUserDTO(
            name: fake()->name(),
            email: fake()->unique()->safeEmail(),
            cpf: fake()->unique()->cpf(),
            phone: fake()->unique()->cellPhoneNumber(),
            password: 'password',
        );
    }
}
