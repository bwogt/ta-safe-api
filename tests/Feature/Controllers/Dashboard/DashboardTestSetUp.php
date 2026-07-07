<?php

namespace Tests\Feature\Controllers\Dashboard;

use App\Models\User;
use Database\Factories\DeviceFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Asserts\AccessAsserts;
use Tests\TestCase;

class DashboardTestSetUp extends TestCase
{
    use AccessAsserts;
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userSetUp();
    }

    private function userSetUp(): void
    {
        $this->user = UserFactory::new()->create();
    }

    protected function generateDevices(): void
    {
        DeviceFactory::new()->for($this->user)->create();
        DeviceFactory::new()->for($this->user)->inAnalysis()->create();
        DeviceFactory::new()->for($this->user)->validated()->create();
        DeviceFactory::new()->for($this->user)->rejected()->create();
    }

    protected function route(): string
    {
        return route('api.dashboard');
    }
}
