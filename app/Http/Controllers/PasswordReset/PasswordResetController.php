<?php

namespace App\Http\Controllers\PasswordReset;

use App\Actions\PasswordReset\Check\CheckPasswordResetCodeAction;
use App\Actions\PasswordReset\Reset\ResetPasswordAction;
use App\Actions\PasswordReset\Start\StartPasswordResetAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\PasswordReset\CheckPasswordResetCodeRequest;
use App\Http\Requests\PasswordReset\ResetPasswordRequest;
use App\Http\Requests\PasswordReset\StartPasswordResetRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class PasswordResetController extends Controller
{
    public function start(
        StartPasswordResetRequest $request,
        StartPasswordResetAction $action
    ): JsonResponse {
        $action($request->email());

        return response()->json(FlashMessage::success(
            __('actions.password_reset.success.start')
        ), Response::HTTP_OK);
    }

    public function checkCode(
        CheckPasswordResetCodeRequest $request,
        CheckPasswordResetCodeAction $action
    ): JsonResponse {
        $action($request->email(), $request->code());

        return response()->json(FlashMessage::success(
            __('actions.password_reset.success.check_code')
        ), Response::HTTP_OK);
    }

    public function reset(
        ResetPasswordRequest $request,
        ResetPasswordAction $action
    ): JsonResponse {
        $action($request->toDto());

        return response()->json(FlashMessage::success(
            __('actions.password_reset.success.reset')
        ), Response::HTTP_OK);
    }
}
