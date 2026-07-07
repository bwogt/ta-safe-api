<?php

namespace Tests\Feature\Controllers\Dashboard;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

final class DashboardResponseTest extends DashboardTestSetUp
{
    public function test_returns_zeroed_dashboard_summary_when_user_has_no_devices(): void
    {
        Sanctum::actingAs($this->user);

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('total', 0)
                ->where('validated', 0)
                ->where('pending', 0)
                ->where('rejected', 0)
                ->where('in_analysis', 0)
            );
    }

    public function test_returns_dashboard_summary_with_device_counts_by_status(): void
    {
        Sanctum::actingAs($this->user);
        $this->generateDevices();

        $this->getJson($this->route())
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('total', 4)
                ->where('validated', 1)
                ->where('pending', 1)
                ->where('rejected', 1)
                ->where('in_analysis', 1)
            );
    }
}
