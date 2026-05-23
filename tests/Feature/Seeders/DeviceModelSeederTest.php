<?php

namespace Tests\Feature\Seeders;

use App\Models\DeviceModel;
use Database\Seeders\Production\BrandSeeder;
use Database\Seeders\Production\DeviceModelSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceModelSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BrandSeeder::class);
    }

    public function test_should_populates_device_models_table(): void
    {
        $this->seed(DeviceModelSeeder::class);
        $this->assertGreaterThan(0, DeviceModel::count());
    }

    public function test_should_is_idempotent_and_does_not_duplicate_device_models(): void
    {
        $this->seed(DeviceModelSeeder::class);
        $firstCount = DeviceModel::count();

        $this->seed(DeviceModelSeeder::class);
        $secondCount = DeviceModel::count();

        $this->assertEquals(
            $firstCount, $secondCount,
            'The device model seeder duplicated records when it ran for the second time.'
        );
    }
}
