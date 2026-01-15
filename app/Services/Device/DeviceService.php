<?php

namespace App\Services\Device;

use App\Actions\Device\Validate\StartDeviceValidationAction;
use App\Dto\Device\Invoice\DeviceInvoiceDto;
use App\Jobs\Device\ValidateDeviceRegistrationJob;
use App\Models\Device;
use App\Models\User;

class DeviceService
{
    public function __construct(
        private readonly User $user
    ) {}

    /**
     * Starts the validation process of a device for the given user.
     */
    public function validate(Device $device, DeviceInvoiceDto $data): Device
    {
        $device = (new StartDeviceValidationAction($this->user, $device, $data))->execute();
        ValidateDeviceRegistrationJob::dispatchAfterResponse($device);

        return $device;
    }
}
