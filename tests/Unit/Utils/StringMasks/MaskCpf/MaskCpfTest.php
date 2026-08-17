<?php

namespace Tests\Unit\Utils\StringMasks\MaskCpf;

use App\Utils\Masks;
use Tests\TestCase;

final class MaskCpfTest extends TestCase
{
    public function test_should_mask_cpf_with_first_and_last_digits_hidden(): void
    {
        $items = ['573.352.530-47', '573.406.220-04', '041.531.500-02'];
        $expected = ['***.352.530-**', '***.406.220-**', '***.531.500-**'];

        foreach ($items as $index => $cpf) {
            $masked = Masks::maskCpf($cpf);
            $this->assertEquals($expected[$index], $masked);
        }
    }
}
