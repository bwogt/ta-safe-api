<?php

namespace Tests\Feature\Controllers\DeviceShareController\View;

final class DeviceShareViewAccessTest extends DeviceShareViewTestSetUp
{
    public function test_an_unauthenticated_user_must_not_be_authorized_to_view_a_device(): void
    {
        $this->assertAccessUnauthorizedTo($this->route(), 'get');
    }

    public function test_an_authenticated_user_with_a_valid_code_must_be_authorized_to_view_a_device(): void
    {
        $this->assertAccessTo(
            route: $this->route($this->code),
            httpVerb: 'get',
            assertHttpResponse: 'assertOk',
            users: [$this->user]
        );
    }
}
