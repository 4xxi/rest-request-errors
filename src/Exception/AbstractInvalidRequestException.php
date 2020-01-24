<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

abstract class AbstractInvalidRequestException extends \RuntimeException implements InvalidRequestExceptionInterface
{
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct('Invalid Request.', 0, $previous);
    }
}
