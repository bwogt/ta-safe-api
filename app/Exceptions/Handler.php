<?php

namespace App\Exceptions;

use App\Exceptions\Application\ApplicationFailsException;
use App\Exceptions\BusinessRules\BusinessRuleException;
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
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        BusinessRuleException::class,
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
