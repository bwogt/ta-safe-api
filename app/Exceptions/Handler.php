<?php

namespace App\Exceptions;

use App\Exceptions\Application\ApplicationFailsException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use App\Http\Messages\FlashMessage;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (ApplicationFailsException $e) {
            $previous = $e->getPrevious();

            Log::error($e->getMessage(), [
                'context' => $e->context(),
                'domain' => $e->domain(),
                'action' => $e->action(),
                'previous' => $previous ? [
                    'exception' => class_basename($previous),
                    'message' => $previous->getMessage(),
                    'code' => $previous->getCode(),
                ] : null,
            ]);
        });

        $this->renderable(function (ApplicationFailsException $e, $request) {
            if (config('app.debug')) {
                return null;
            }

            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(
                    FlashMessage::error(trans('actions.' . $e->translationKey())),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        });

        $this->reportable(function (BusinessRuleException $e) {
            Log::error($e->getMessage(), [
                'context' => $e->context(),
                'domain' => $e->domain(),
                'rule_violated' => $e->ruleViolated(),
            ]);
        });

        $this->renderable(function (BusinessRuleException $e, $request) {
            if (config('app.debug')) {
                return null;
            }

            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(
                    FlashMessage::error(trans('validators.' . $e->translationKey())),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        });

        $this->renderable(function (AuthenticationException $e) {
            return response()->json(
                FlashMessage::error(trans('http_exceptions.unauthenticated')),
                Response::HTTP_UNAUTHORIZED
            );
        });

        $this->renderable(function (HttpException $e) {
            if ($e->getStatusCode() === 403) {
                return response()->json(
                    FlashMessage::error(trans('http_exceptions.unauthorized')),
                    $e->getStatusCode()
                );
            }

            if ($e->getStatusCode() === 404) {
                return response()->json(
                    FlashMessage::error(trans('http_exceptions.not_found')),
                    $e->getStatusCode()
                );
            }

            if ($e->getStatusCode() === 429) {
                return response()->json(
                    FlashMessage::error(trans('http_exceptions.too_many_attempts')),
                    $e->getStatusCode()
                );
            }
        });
    }
}
