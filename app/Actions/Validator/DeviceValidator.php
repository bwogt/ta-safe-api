<?php

namespace App\Actions\Validator;

use App\Exceptions\BusinessRules\Device\DeviceNotOwnedException;
use App\Exceptions\BusinessRules\Device\DeviceStatusIsNotPendingException;
use App\Exceptions\BusinessRules\Device\DeviceStatusIsNotRejectedException;
use App\Exceptions\HttpJsonResponseException;
use App\Models\Device;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class DeviceValidator
{
    public function __construct(
        private readonly Device $device
    ) {}

    /**
     * Create a new DeviceValidator instance for the specified device.
     */
    public static function for(Device $device): self
    {
        return new self($device);
    }

    public static function mustBeOwner(User $user, Device $device): void
    {
        $isOwner = $user->id === $device->user_id;
        throw_unless($isOwner, new DeviceNotOwnedException);
    }

    public static function statusMustBeRejected(Device $device): void
    {
        $isRejected = $device->validation_status->isRejected();
        throw_unless($isRejected, new DeviceStatusIsNotRejectedException);
    }

    /**
     * Validate if the device status is 'validated'.
     */
    public function statusMustBeValidated(): self
    {
        $isValidate = $this->device->validation_status->isValidated();

        throw_unless($isValidate, new HttpJsonResponseException(
            trans('validators.device.status.validated'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));

        return $this;
    }

    public static function statusMustBePending(Device $device): void
    {
        $isPending = $device->validation_status->isPending();
        throw_unless($isPending, new DeviceStatusIsNotPendingException);
    }

    /**
     * Validate if the device status is 'in_analysis'.
     */
    public function statusMustBeInAnalysis(): self
    {
        $isInAnalysis = $this->device->validation_status->isInAnalysis();

        throw_unless($isInAnalysis, new HttpJsonResponseException(
            trans('validators.device.status.in_analysis'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));

        return $this;
    }
}
