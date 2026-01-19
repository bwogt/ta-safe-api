<?php

namespace App\Exceptions\Helpers;

use App\Exceptions\Application\ApplicationFailsException;
use App\Exceptions\BusinessRules\BusinessRuleException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ExceptionMapper
{
    public static function map(Throwable $e): array
    {
        return [
            'code' => self::defineCode($e),
            'message' => self::defineMessage($e),
        ];
    }

    private static function defineMessage(Throwable $e): string
    {
        return match (true) {
            $e instanceof ApplicationFailsException => trans('actions.' . $e->translationKey()),
            $e instanceof BusinessRuleException => trans('validators.' . $e->translationKey()),
            $e instanceof AuthenticationException => trans('http_exceptions.unauthenticated'),
            $e instanceof HttpException => self::mapHttpStatusCode($e->getStatusCode()),
            default => trans('http_exceptions.internal_server_error'),
        };
    }

    private static function defineCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof BusinessRuleException => Response::HTTP_UNPROCESSABLE_ENTITY,
            $e instanceof AuthenticationException => Response::HTTP_UNAUTHORIZED,
            $e instanceof HttpException => $e->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    private static function mapHttpStatusCode(int $code): string
    {
        return match ($code) {
            Response::HTTP_FORBIDDEN => trans('http_exceptions.unauthorized'),
            Response::HTTP_NOT_FOUND => trans('http_exceptions.not_found'),
            Response::HTTP_TOO_MANY_REQUESTS => trans('http_exceptions.too_many_attempts'),
            default => trans('http_exceptions.internal_server_error'),
        };
    }
}
