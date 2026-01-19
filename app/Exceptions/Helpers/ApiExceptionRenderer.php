<?php

namespace App\Exceptions\Helpers;

use App\Http\Messages\FlashMessage;
use Throwable;

class ApiExceptionRenderer
{
    public function __invoke(Throwable $e, $request)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            $result = ExceptionMapper::map($e);

            return response()->json(
                FlashMessage::error($result['message']),
                $result['code']
            );
        }

        return null;
    }
}
