<?php

namespace App\Exceptions\Helpers;

use App\Exceptions\BusinessRules\BusinessRuleException;
use Illuminate\Support\Facades\Log;

class BusinessRuleExceptionLogger
{
    public function __invoke(BusinessRuleException $e): void
    {
        Log::warning($e->getMessage(), [
            'domain' => $e->domain(),
            'rule_violated' => $e->ruleViolated(),
            'context' => $e->context(),
        ]);
    }
}
