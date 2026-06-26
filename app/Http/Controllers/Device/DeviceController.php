<?php

namespace App\Http\Controllers\Device;

use App\Actions\Device\Delete\DeleteDeviceAction;
use App\Actions\Device\Register\RegisterDeviceAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Device\RegisterDeviceRequest;
use App\Http\Resources\Device\DeviceResource;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class DeviceController extends Controller
{
    public function view(Device $device): JsonResource
    {
        $this->authorize('accessAsOwner', $device);

        $device->loadMissing([
            'attributeValidationLogs',
            'transfers' => fn ($q) => $q->acceptedAndOrdered(),
        ]);

        return new DeviceResource($device);
    }

    public function register(RegisterDeviceRequest $request, RegisterDeviceAction $action): JsonResponse
    {
        $action(($request->user()), $request->toDto());

        return response()->json(FlashMessage::success(
            trans('actions.device.success.register')),
            Response::HTTP_CREATED,
        );
    }

    public function delete(Device $device, DeleteDeviceAction $action): Response
    {
        $this->authorize('accessAsOwner', $device);

        $action(request()->user(), $device);

        return response()->json(FlashMessage::success(
            trans('actions.device.success.delete')),
            Response::HTTP_OK
        );
    }
}
