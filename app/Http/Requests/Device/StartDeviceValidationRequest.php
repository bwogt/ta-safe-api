<?php

namespace App\Http\Requests\Device;

use App\Dto\Device\DeviceInvoiceDTO;
use Illuminate\Foundation\Http\FormRequest;

class StartDeviceValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('accessAsOwner', $this->device);
    }

    public function toDto(): DeviceInvoiceDTO
    {
        return new DeviceInvoiceDTO(
            cpf: $this->input('cpf'),
            name: $this->input('name'),
            products: $this->input('products')
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
