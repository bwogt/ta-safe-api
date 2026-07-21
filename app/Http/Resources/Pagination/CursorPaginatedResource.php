<?php

namespace App\Http\Resources\Pagination;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\CursorPaginator;

final class CursorPaginatedResource extends JsonResource
{
    public static function from(string $resource, CursorPaginator $paginator): self
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
                'has_more_page' => $paginator->hasMorePages(),
                'next_cursor' => $paginator->nextCursor()?->encode(),
            ],
        ];
    }
}
