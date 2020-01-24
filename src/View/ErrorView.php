<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\View;

final class ErrorView
{
    /**
     * @var string[]
     */
    public $errors;

    /**
     * @var ErrorView[]
     */
    public $children;
}
