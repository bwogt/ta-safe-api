<?php

namespace Tests\Unit\Exception\Mapper;

use App\Exceptions\Application\Auth\LoginFailedException;
use App\Exceptions\BusinessRules\Auth\InvalidCredentialsException;
use App\Exceptions\Helpers\ExceptionMapper;
use Exception;
use Illuminate\Auth\AuthenticationException;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class ExceptionMapperTest extends TestCase
{
    public function test_application_exception_is_mapped_to_500(): void
    {
        $previous = new RuntimeException('error');
        $exception = new LoginFailedException($previous, []);
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result['code']);
        $this->assertEquals($result['message'], trans('actions.auth.errors.login'));
    }

    public function test_business_rule_exception_is_mapped_to_422(): void
    {
        $exception = new InvalidCredentialsException;
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $result['code']);
        $this->assertEquals($result['message'], trans('validators.auth.invalid_credentials'));
    }

    public function test_authentication_exception_is_mapped_to_401(): void
    {
        $exception = new AuthenticationException;
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $result['code']);
        $this->assertEquals($result['message'], trans('http_exceptions.unauthenticated'));
    }

    public function test_http_forbidden_is_mapped_to_unauthorized_message(): void
    {
        $exception = new HttpException(Response::HTTP_FORBIDDEN);
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_FORBIDDEN, $result['code']);
        $this->assertEquals($result['message'], trans('http_exceptions.unauthorized'));
    }

    public function test_http_not_found_is_mapped_to_not_found_message(): void
    {
        $exception = new HttpException(Response::HTTP_NOT_FOUND);
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $result['code']);
        $this->assertEquals($result['message'], trans('http_exceptions.not_found'));
    }

    public function test_http_too_many_requests_is_mapped_to_too_many_attempts_message(): void
    {
        $exception = new HttpException(Response::HTTP_TOO_MANY_REQUESTS);
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_TOO_MANY_REQUESTS, $result['code']);
        $this->assertEquals($result['message'], trans('http_exceptions.too_many_attempts'));
    }

    public function test_generic_exception_is_mapped_to_500_by_default(): void
    {
        $exception = new Exception;
        $result = ExceptionMapper::map($exception);

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $result['code']);
        $this->assertEquals($result['message'], trans('http_exceptions.internal_server_error'));
    }
}
