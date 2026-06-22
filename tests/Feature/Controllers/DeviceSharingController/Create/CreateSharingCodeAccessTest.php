<?php

namespace Tests\Feature\Controllers\DeviceSharingController\Create;

use Database\Factories\UserFactory;

final class CreateSharingCodeAccessTest extends CreateSharingCodeTestSetUp
{
    public function test_should_not_allow_unauthenticated_user_to_create_device_sharing_code(): void
    {
        $this->assertAccessUnauthorizedTo($this->route(), 'post');
    }

    public function test_should_allow_owner_to_create_device_sharing_code(): void
    {
        $this->assertAccessTo(
            route: $this->route(),
            httpVerb: 'post',
            assertHttpResponse: 'assertCreated',
            users: [$this->user]
        );
    }

    public function test_should_not_allow_non_owner_to_create_device_sharing_code(): void
    {
        $this->assertNoAccessTo(
            route: $this->route(),
            httpVerb: 'post',
            users: [UserFactory::new()->create()]
        );
    }
}
