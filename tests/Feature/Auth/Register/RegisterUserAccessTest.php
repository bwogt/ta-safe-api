<?php

namespace Tests\Feature\Auth\Register;

use App\Enums\FlashMessage\FlashMessageType;
use Illuminate\Testing\Fluent\AssertableJson;

class RegisterUserAccessTest extends RegisterUserTestSetUp
{
    public function test_should_an_unauthenticated_user_be_able_to_register(): void
    {
        $data = $this->validUserData();

        $this->postJson($this->route(), $data)
            ->assertCreated()
            ->assertJson(fn (AssertableJson $json) => $json->where('message.type', FlashMessageType::SUCCESS)
                ->where('message.text', trans('actions.auth.success.register'))
                ->where('data.user.name', $data['name'])
                ->where('data.user.email', $data['email'])
                ->where('data.user.cpf', $data['cpf'])
                ->where('data.user.phone', $data['phone'])
                ->has('data.token')
                ->missing('data.user.password')
            );
    }
}
