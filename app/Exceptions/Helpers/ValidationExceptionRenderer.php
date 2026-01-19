<?php

namespace App\Exceptions\Helpers;

use App\Http\Messages\FlashMessage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ValidationExceptionRenderer
{
    public function __invoke(ValidationException $e, $request)
    {
        $response = FlashMessage::error(
            trans('flash_messages.errors')
        )->toArray($request);

        $response['errors'] = $e->errors();

        return response()->json(
            $response,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
