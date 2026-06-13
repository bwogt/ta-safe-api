<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Login\LoginAction;
use App\Actions\Auth\Register\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\Auth\LoginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, RegisterUserAction $action): JsonResponse
    {
        return response()->json(
            new LoginResource($action($request->toDto())),
            Response::HTTP_CREATED
        );
    }

    public function login(LoginRequest $request, LoginAction $action): JsonResource
    {
        return new LoginResource($action($request->toDto()));
    }

    public function logout(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return response()->json(
            FlashMessage::success(trans('actions.auth.success.logout')),
            Response::HTTP_OK
        );
    }
}
