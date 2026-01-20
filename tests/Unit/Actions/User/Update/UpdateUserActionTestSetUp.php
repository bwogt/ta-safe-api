<?php

namespace Tests\Unit\Actions\User\Update;

use App\Actions\User\Update\UpdateUserAction;
use App\Dto\User\UpdateUserDTO;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class UpdateUserActionTestSetUp extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected UpdateUserDTO $data;
    protected UpdateUserAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userSetUp();
        $this->actionSetUp();
        $this->dataSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    private function actionSetUp(): void
    {
        $this->action = new UpdateUserAction;
    }

    private function dataSetUp(): void
    {
        $this->data = new UpdateUserDTO(
            name: fake()->name(),
            email: fake()->unique()->safeEmail(),
            phone: fake()->unique()->cellPhoneNumber(),
        );
    }
}
