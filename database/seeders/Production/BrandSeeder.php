<?php

namespace Database\Seeders\Production;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = $this->loadBrandsFile();

        foreach ($brands as $rawBrand) {
            Brand::updateOrCreate(['name' => $rawBrand->name]);
        }
    }

    private function loadBrandsFile(): mixed
    {
        $json = File::get(database_path('data/production/brands.json'));

        return json_decode($json);
    }
}
