<?php

namespace Tests\Feature\Seeders;

use App\Models\Brand;
use Database\Seeders\Production\BrandSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_populates_brands_table(): void
    {
        $this->seed(BrandSeeder::class);
        $this->assertGreaterThan(0, Brand::count());
    }

    public function test_should_is_idempotent_and_does_not_duplicate_brands(): void
    {
        $this->seed(BrandSeeder::class);
        $firstCount = Brand::count();

        $this->seed(BrandSeeder::class);
        $secondCount = Brand::count();

        $this->assertEquals(
            $firstCount, $secondCount,
            'The brand seeder duplicated records when it ran for the second time.'
        );
    }
}
