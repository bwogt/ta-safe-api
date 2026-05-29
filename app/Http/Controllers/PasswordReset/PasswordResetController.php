<?php

namespace App\Http\Controllers\PasswordReset;

use App\Actions\PasswordReset\Check\CheckPasswordResetCodeAction;
use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\PasswordReset\CheckPasswordResetCodeRequest;
use App\Http\Requests\PasswordReset\StartPasswordResetRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetController extends Controller
{
    public function start(
        StartPasswordResetRequest $request,
        StartPasswordResetAction $action
    ): JsonResponse {
        $action($request->email());

        return response()->json(
            FlashMessage::success(trans('actions.password_reset.success.start')),
            Response::HTTP_OK
        );
    }

    public function checkCode(
        CheckPasswordResetCodeRequest $request,
        CheckPasswordResetCodeAction $action
    ): JsonResponse {
        $action($request->email(), $request->code());

        return response()->json(
            FlashMessage::success(trans('actions.password_reset.check_code')),
            Response::HTTP_OK
        );
    }
}
