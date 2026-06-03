<?php

namespace Database\Seeders\Development;

use Database\Seeders\Production\BrandSeeder;
use Database\Seeders\Production\DeviceModelSeeder;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            $this->call([
                UserSeeder::class,
                BrandSeeder::class,
                DeviceModelSeeder::class,
                DeviceSeeder::class,
            ]);
        }
    }
}
