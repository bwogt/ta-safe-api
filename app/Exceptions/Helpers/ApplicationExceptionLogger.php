<?php

namespace App\Exceptions\Helpers;

use App\Exceptions\Application\ApplicationFailsException;
use Illuminate\Support\Facades\Log;

class ApplicationExceptionLogger
{
    public function __invoke(ApplicationFailsException $e): void
    {
        $previous = $e->getPrevious();

        Log::error($e->getMessage(), [
            'domain' => $e->domain(),
            'action' => $e->action(),
            'context' => $e->context(),
            'previous' => $previous ? [
                'exception' => class_basename($previous),
                'message' => $previous->getMessage(),
                'code' => $previous->getCode(),
            ] : null,
        ]);
    }
}
