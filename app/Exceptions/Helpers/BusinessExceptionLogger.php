<?php

namespace App\Exceptions\Helpers;

use App\Exceptions\BusinessRules\BusinessRuleException;
use Illuminate\Support\Facades\Log;

class BusinessExceptionLogger
{
    public function __invoke(BusinessRuleException $e): void
    {
        Log::error($e->getMessage(), [
            'domain' => $e->domain(),
            'rule_violated' => $e->ruleViolated(),
            'context' => $e->context(),
        ]);
    }
}
