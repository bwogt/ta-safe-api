<?php

namespace App\Utils;

final class Masks
{
    public static function maskCpf(string $cpf): string
    {
        return '***.' . substr($cpf, 4, 7) . '-**';
    }
}
