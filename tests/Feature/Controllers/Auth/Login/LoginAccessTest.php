<?php

namespace Tests\Feature\Controllers\Auth\Login;

use Illuminate\Testing\Fluent\AssertableJson;

class LoginAccessTest extends LoginTestSetUp
{
    public function test_a_user_should_be_able_to_login(): void
    {
        $this->postJson($this->route(), [
            'email' => $this->user->email,
            'password' => 'password',
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('user.id', $this->user->id)
                ->where('user.name', $this->user->name)
                ->where('user.email', $this->user->email)
                ->where('user.cpf', $this->user->cpf)
                ->missing('user.password')
                ->has('token')
            );
    }
}
