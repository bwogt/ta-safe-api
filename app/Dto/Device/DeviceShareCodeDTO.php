<?php

namespace App\Dto\Device;

final class DeviceShareCodeDTO
{
    public function __construct(
        public readonly string $code,
        public readonly int $ttl
    ) {}
}
