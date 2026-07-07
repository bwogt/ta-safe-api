<?php

namespace Tests\Feature\Controllers\Dashboard;

final class DashboardAccessTest extends DashboardTestSetUp
{
    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $this->assertAccessUnauthorizedTo($this->route(), 'get');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $this->assertAccessTo(
            route: $this->route(),
            httpVerb: 'get',
            assertHttpResponse: 'assertOk',
            users: [$this->user],
        );
    }
}
