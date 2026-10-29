<?php

namespace Tests\Feature\Controllers\DeviceTransferController\View;

use Database\Factories\UserFactory;

final class ViewDeviceTransferAccessTest extends ViewDeviceTransferTestSetUp
{
    public function test_unauthenticated_user_should_not_view_device_transfer(): void
    {
        $this->assertAccessUnauthorizedTo($this->route(), 'get');
    }

    public function test_source_user_should_view_device_transfer(): void
    {
        $this->assertAccessTo(
            route: $this->route(),
            httpVerb: 'get',
            assertHttpResponse: 'assertOk',
            users: [$this->sourceUser]
        );
    }

    public function test_target_user_should_view_device_transfer(): void
    {
        $this->assertAccessTo(
            route: $this->route(),
            httpVerb: 'get',
            assertHttpResponse: 'assertOk',
            users: [$this->targetUser]
        );
    }

    public function test_unrelated_user_should_not_view_device_transfer(): void
    {
        $this->assertNoAccessTo(
            route: $this->route(),
            httpVerb: 'get',
            users: [UserFactory::new()->create()]
        );
    }
}
