<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Fourxxi\RestRequestError\Exception\Model\ErrorTree;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;

interface InvalidRequestExceptionInterface
{
    public function getErrors(): ErrorTree;
}
