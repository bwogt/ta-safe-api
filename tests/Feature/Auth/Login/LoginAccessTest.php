<?php

namespace Tests\Feature\Auth\Login;

use App\Enums\FlashMessage\FlashMessageType;
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
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('actions.auth.success.login'))
                ->where('data.user.id', $this->user->id)
                ->where('data.user.name', $this->user->name)
                ->where('data.user.email', $this->user->email)
                ->where('data.user.cpf', $this->user->cpf)
                ->where('data.user.phone', $this->user->phone)
                ->has('data.token')
                ->missing('data.user.password')
            );
    }
}
