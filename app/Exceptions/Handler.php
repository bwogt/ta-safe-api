<?php

namespace App\Exceptions;

use App\Exceptions\Application\ApplicationFailsException;
use App\Exceptions\Helpers\ApiExceptionRenderer;
use App\Exceptions\Helpers\ApplicationExceptionLogger;
use App\Exceptions\Helpers\ValidationExceptionRenderer;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(fn (ApplicationFailsException $e) => (new ApplicationExceptionLogger)($e));

        $this->renderable(fn (ValidationException $e, $request) => (new ValidationExceptionRenderer)($e, $request));
        $this->renderable(fn (Throwable $e, $request) => (new ApiExceptionRenderer)($e, $request));
    }
}
