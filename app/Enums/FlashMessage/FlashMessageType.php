<?php

namespace App\Enums\FlashMessage;

enum FlashMessageType: string
{
    case SUCCESS = 'success';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isInfo(): bool
    {
        return $this === self::INFO;
    }

    public function isWarning(): bool
    {
        return $this === self::WARNING;
    }

    public function isError(): bool
    {
        return $this === self::ERROR;
    }
}
