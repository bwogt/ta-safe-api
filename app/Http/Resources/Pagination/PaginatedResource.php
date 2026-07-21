<?php

namespace App\Http\Resources\Pagination;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

final class PaginatedResource extends JsonResource
{
    public static function from(string $resource, LengthAwarePaginator $paginator): self
    {
        return new self([
            'resource' => $resource,
            'paginator' => $paginator,
        ]);
    }

    public function toArray(Request $request): array
    {
        $resource = $this['resource'];
        $paginator = $this['paginator'];

        return [
            'data' => $resource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'has_next_page' => $paginator->hasMorePages(),
                'total' => $paginator->total(),
            ],
        ];
    }
}
