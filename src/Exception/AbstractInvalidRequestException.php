<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractInvalidRequestException extends HttpException implements InvalidRequestExceptionInterface
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'Bad request', $previous);
    }
}
