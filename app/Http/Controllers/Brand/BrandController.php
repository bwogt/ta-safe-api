<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Resources\Json\JsonResource;

final class BrandController extends Controller
{
    public function brands(): JsonResource
    {
        return BrandResource::collection(Brand::all());
    }
}
