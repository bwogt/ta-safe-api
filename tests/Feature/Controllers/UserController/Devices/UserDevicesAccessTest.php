<?php

namespace Tests\Feature\Controllers\UserController\Devices;

use App\Enums\Device\DeviceValidationStatus;

final class UserDevicesAccessTest extends UserDevicesTestSetUp
{
    public function test_an_unauthenticated_user_should_not_allowed_to_view_devices_by_status(): void
    {
        $this->assertAccessUnauthorizedTo($this->route(status: DeviceValidationStatus::PENDING), 'get');
    }

    public function test_an_authenticated_user_should_be_allowed_to_view_their_devices_by_status(): void
    {
        $this->assertAccessTo(
            route: $this->route(status: DeviceValidationStatus::PENDING),
            httpVerb: 'get',
            assertHttpResponse: 'assertOk',
            users: [$this->user]
        );
    }
}
