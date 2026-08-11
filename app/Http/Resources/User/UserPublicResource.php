<?php

namespace App\Http\Resources\User;

use App\Utils\Masks;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserPublicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => Masks::maskCpf($this->cpf),
            'created_at' => $this->created_at,
        ];
    }
}
