<?php

namespace App\Http\Controllers\Device;

use App\Actions\Device\Share\DeviceShareGenerateAction;
use App\Actions\Device\Share\DeviceShareViewAction;
use App\Http\Controllers\Controller;
use App\Http\Messages\FlashMessage;
use App\Http\Requests\Device\DeviceShareViewRequest;
use App\Http\Resources\Device\DevicePublicResource;
use App\Models\Device;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

final class DeviceShareController extends Controller
{
    public function generate(
        DeviceShareGenerateAction $action,
        Device $device
    ): Response {
        $this->authorize('accessAsOwner', $device);
        $code = $action(request()->user(), $device);

        return response()->json(FlashMessage::success(
            __('actions.device_share.success.generate'))->merge([
                'code' => $code,
            ]), Response::HTTP_CREATED
        );
    }

    public function view(
        DeviceShareViewRequest $request,
        DeviceShareViewAction $action
    ): JsonResource {
        $device = $action(request()->user(), $request->code);

        return new DevicePublicResource($device);
    }
}
