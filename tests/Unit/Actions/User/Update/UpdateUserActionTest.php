<?php

namespace Tests\Unit\Actions\User\Update;

use App\Exceptions\Application\User\UpdateUserFailedException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

final class UpdateUserActionTest extends UpdateUserActionTestSetUp
{
    public function test_should_return_an_instance_of_user_when_the_action_is_successful(): void
    {
        $result = ($this->action)($this->user, $this->data);
        $this->assertInstanceOf(User::class, $result);
    }

    public function test_should_update_the_user_profile_with_the_given_data(): void
    {
        ($this->action)($this->user, $this->data);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => $this->data->name,
            'email' => $this->data->email,
            'phone' => $this->data->phone,
        ]);
    }

    public function test_should_thrown_update_user_failed_exception_on_failure(): void
    {
        $this->expectException(UpdateUserFailedException::class);

        DB::shouldReceive('transaction')->once()
            ->andThrow(new Exception('Simulates a transaction error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            ));

        ($this->action)($this->user, $this->data);
    }
}
