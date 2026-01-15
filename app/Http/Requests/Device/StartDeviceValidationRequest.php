<?php

namespace App\Http\Requests\Device;

use App\Dto\Device\DeviceInvoiceDTO;
use App\Http\Requests\ApiFormRequest;

class StartDeviceValidationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('accessAsOwner', $this->device);
    }

    public function toDto(): DeviceInvoiceDTO
    {
        return new DeviceInvoiceDTO(
            cpf: $this->cpf,
            name: $this->name,
            products: $this->products
        );
    }

    public function rules(): array
    {
        return [
            'cpf' => ['required', 'string', 'max:16'],
            'name' => ['required', 'string', 'max:255'],
            'products' => ['required', 'string', 'max:16000'],
        ];
    }
}
