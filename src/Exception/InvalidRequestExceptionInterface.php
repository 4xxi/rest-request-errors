<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Fourxxi\RestRequestError\Exception\Model\ErrorTree;

interface InvalidRequestExceptionInterface extends \Throwable
{
    public function getErrors(): ErrorTree;
}
