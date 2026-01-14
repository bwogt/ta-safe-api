<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Register\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, RegisterUserAction $action): JsonResponse
    {
        $loginDto = $action($request->toDto());

        return response()->json(
            FlashMessage::success(trans('actions.auth.success.register'))
                ->merge(['data' => new LoginResource($loginDto)]),
            Response::HTTP_CREATED
        );
    }

    public function login(LoginRequest $request, AuthService $auth): JsonResponse
    {
        $loginDto = $auth->login($request->email, $request->password);

        return response()->json(
            FlashMessage::success(trans('actions.auth.success.login'))
                ->merge(['data' => new UserLoginResource($loginDto)]),
            Response::HTTP_OK
        );
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
