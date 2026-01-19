<?php

namespace App\Exceptions\Helpers;

use App\Exceptions\BusinessRules\BusinessRuleException;
use Illuminate\Support\Facades\Log;

class BusinessExceptionLogger
{
    public function __invoke(BusinessRuleException $e): void
    {
        Log::error($e->getMessage(), [
            'context' => $e->context(),
            'domain' => $e->domain(),
            'rule_violated' => $e->ruleViolated(),
        ]);
    }
}
