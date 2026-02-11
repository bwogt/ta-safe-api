<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HttpRequestLogging
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $this->shareContext($request);

        $response = $next($request);

        $this->logRequestFinished($request, $response, $start);
        return $response;
    }

    private function shareContext(Request $request): void
    {
        $requestId = $request->headers->get('X-Request-Id')
            ?? (string) Str::uuid();

        Log::shareContext([
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => '/' . ltrim($request->path(), '/'),
        ]);

        if ($userId = $request->user()?->id) {
            Log::shareContext(['user_id' => $userId]);
        }
    }

    private function logRequestFinished(Request $request, Response $response, float $start): void
    {
        $durationMs = round((microtime(true) - $start) * 1000, 2);

        Log::info('HTTP Request Finished', [
            'status' => $response->getStatusCode(),
            'duration_ms' => $durationMs,
            'route_name' => $request->route()?->getName() ?? 'unnamed',
        ]);
    }
}
