<?php

namespace App\Exceptions\BusinessRules;

use DomainException;
abstract class BusinessRuleException extends DomainException
{
    public function __construct(
        private array $context = [],
    ) {
        parent::__construct($this->defaultMessage());
    }

    abstract public function domain(): string;
    abstract public function ruleViolated(): string;
    abstract public function defaultMessage(): string;

    public function context(): array
    {
        return $this->context;
    }

    public function translationKey(): string
    {
        return "{$this->domain()}.{$this->ruleViolated()}";
    }
}
