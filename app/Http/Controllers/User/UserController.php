<?php

namespace App\Http\Controllers\User;

use App\Actions\User\Update\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\User\SearchUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Device\DeviceResource;
use App\Http\Resources\DeviceTransfer\DeviceTransferResource;
use App\Http\Resources\User\UserPublicResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function update(UpdateUserRequest $request, UpdateUserAction $action): Response
    {
        $user = $action($request->user(), $request->toDto());

        return response()->json(FlashMessage::success(
            __('actions.user.success.update'))->merge([
                'user' => new UserResource($user),
            ]), Response::HTTP_OK
        );
    }

    public function view(Request $request): JsonResource
    {
        return new UserResource($request->user());
    }

    public function searchByEmail(SearchUserRequest $request): JsonResource
    {
        return new UserPublicResource($request->userByEmail());
    }

    public function devices(Request $request): JsonResource
    {
        return DeviceResource::collection($request->user()->devicesOrderedByUpdate());
    }

    public function transfers(Request $request): JsonResource
    {
        return DeviceTransferResource::collection($request->user()->userDevicesTransfers());
    }
}
