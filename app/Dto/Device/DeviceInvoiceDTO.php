<?php

namespace App\Dto\Device;

final class DeviceInvoiceDTO
{
    public function __construct(
        public readonly string $cpf,
        public readonly string $name,
        public readonly string $products
    ) {}
}
