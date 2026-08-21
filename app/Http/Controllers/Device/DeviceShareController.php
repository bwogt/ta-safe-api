<?php

namespace App\Http\Controllers\Device;

use App\Actions\Device\Share\CreateDeviceShareCodeAction;
use App\Actions\Device\Share\GetDeviceByShareCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Device\GetDeviceByShareCodeRequest;
use App\Http\Resources\Device\DevicePublicResource;
use App\Http\Resources\Device\DeviceShareCodeResource;
use App\Models\Device;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final class DeviceShareController extends Controller
{
    public function create(
        CreateDeviceShareCodeAction $action,
        Device $device
    ): Response {
        $this->authorize('accessAsOwner', $device);
        $action(request()->user(), $device);

        return DeviceShareCodeResource::make($device->activeShareCode())
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function view(
        GetDeviceByShareCodeRequest $request,
        GetDeviceByShareCodeAction $action
    ): JsonResource {
        $device = $action(request()->user(), $request->code);

        return new DevicePublicResource($device);
    }
}
