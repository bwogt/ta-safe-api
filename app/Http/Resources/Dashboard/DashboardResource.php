<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => $this->resource->sum(),
            'pending' => $this->resource->get('pending', 0),
            'validated' => $this->resource->get('validated', 0),
            'rejected' => $this->resource->get('rejected', 0),
            'in_analysis' => $this->resource->get('in_analysis', 0),
        ];
    }
}
