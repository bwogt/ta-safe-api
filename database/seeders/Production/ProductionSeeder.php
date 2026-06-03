<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->call([
                BrandSeeder::class,
                DeviceModelSeeder::class,
            ]);
        }
    }
}
