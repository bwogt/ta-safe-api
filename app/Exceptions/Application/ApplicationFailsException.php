<?php

namespace App\Exceptions\Application;

use Exception;
use Throwable;

abstract class ApplicationFailsException extends Exception
{
    public function __construct(
        Throwable $previous,
        private array $context,
        ?int $code = 0,
    ) {
        parent::__construct($this->defaultMessage(), $code, $previous);
    }

    abstract public function domain(): string;
    abstract public function action(): string;
    abstract public function defaultMessage(): string;

    public function context(): array
    {
        return $this->context;
    }

    public function translationKey(): string
    {
        return "{$this->domain()}.errors.{$this->action()}";
    }
}
