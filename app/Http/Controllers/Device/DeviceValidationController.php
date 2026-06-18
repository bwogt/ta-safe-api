<?php

namespace App\Http\Controllers\Device;

use App\Actions\Device\Invalidate\InvalidateDeviceAction;
use App\Actions\Device\Validate\StartDeviceValidationAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Device\StartDeviceValidationRequest;
use App\Http\Resources\Device\DeviceResource;
use App\Jobs\Device\ValidateDeviceRegistrationJob;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final Class DeviceValidationController extends Controller
{
    public function start(
        StartDeviceValidationRequest $request,
        StartDeviceValidationAction $action,
        Device $device
    ): JsonResponse {
        $action($request->user(), $device, $request->toDto());
        ValidateDeviceRegistrationJob::dispatch($device);

        return response()->json(FlashMessage::success(
            trans('actions.device.success.validate'))->merge([
                'device' => new DeviceResource($device),
            ]), Response::HTTP_OK
        );
    }

    public function invalidate(Device $device, InvalidateDeviceAction $action): JsonResponse
    {
        $this->authorize('accessAsOwner', $device);

        $action(request()->user(), $device);

        return response()->json(FlashMessage::success(
            trans('actions.device.success.invalidate'))->merge([
                'device' => new DeviceResource($device),
            ]), Response::HTTP_OK
        );
    }
}