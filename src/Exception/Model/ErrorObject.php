<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception\Model;

final class ErrorObject
{
    /**
     * @var string
     */
    private $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
