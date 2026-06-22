<?php

namespace App\Http\Controllers\Device;

use App\Actions\Device\Share\CreateDeviceSharingCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Device\ViewDeviceByTokenRequest;
use App\Http\Resources\Device\DevicePublicResource;
use App\Models\Device;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class DeviceSharingController extends Controller
{
    public function createSharingCode(
        CreateDeviceSharingCodeAction $action,
        Device $device
    ): Response {
        $this->authorize('accessAsOwner', $device);
        $code = $action(request()->user(), $device);

        return response()->json(FlashMessage::success(
            __('actions.device_sharing.success.create'))->merge([
                'code' => $code,
            ]), Response::HTTP_CREATED
        );
    }

    /**
     * View device by sharing token.
     */
    public function viewDeviceByToken(ViewDeviceByTokenRequest $request): JsonResource
    {
        return new DevicePublicResource($request->deviceSharingToken()->device);
    }
}
