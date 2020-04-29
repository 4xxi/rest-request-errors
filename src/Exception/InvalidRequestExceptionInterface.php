<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Fourxxi\RestRequestError\Exception\Model\ErrorTree;

interface InvalidRequestExceptionInterface
{
    public function getErrors(): ErrorTree;

    public function getStatusCode();
}
