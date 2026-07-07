<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardResource;
use Illuminate\Http\Resources\Json\JsonResource;

final class DashboardController extends Controller
{
    public function dashboard(): JsonResource
    {
        $user = request()->user();

        $stats = $user->devices()
            ->selectRaw('validation_status, count(*) as total')
            ->groupBy('validation_status')
            ->pluck('total', 'validation_status');

        return DashboardResource::make($stats);
    }
}
