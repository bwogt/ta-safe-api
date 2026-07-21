<?php

namespace App\Http\Controllers\User;

use App\Actions\User\Devices\DevicesByStatusAction;
use App\Actions\User\Update\UpdateUserAction;
use App\Enums\Device\DeviceValidationStatus;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\User\SearchUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\Device\DeviceResource;
use App\Http\Resources\DeviceTransfer\DeviceTransferResource;
use App\Http\Resources\Pagination\CursorPaginatedResource;
use App\Http\Resources\Pagination\PaginatedResource;
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

    public function devices(
        DevicesByStatusAction $action,
        DeviceValidationStatus $status
    ): JsonResource {
        $user = request()->user();
        $devices = $action($user, $status);

        return CursorPaginatedResource::from(DeviceResource::class, $devices);
    }

    public function transfers(Request $request): JsonResource
    {
        return DeviceTransferResource::collection($request->user()->userDevicesTransfers());
    }
}
