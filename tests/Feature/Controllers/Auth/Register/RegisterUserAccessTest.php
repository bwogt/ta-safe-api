<?php

namespace Tests\Feature\Controllers\Auth\Register;

use Illuminate\Testing\Fluent\AssertableJson;

class RegisterUserAccessTest extends RegisterUserTestSetUp
{
    public function test_should_an_unauthenticated_user_be_able_to_register(): void
    {
        $data = $this->data();

        $this->postJson($this->route(), $data)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('user.name', $data['name'])
                ->where('user.email', $data['email'])
                ->where('user.cpf', $data['cpf'])
                ->missing('user.password')
                ->has('token')
            );
    }
}
