<?php

namespace App\Dto\Device;

final class RegisterDeviceDTO
{
    public function __construct(
        public readonly int $deviceModelId,
        public readonly string $accessKey,
        public readonly string $color,
    ) {}
}
