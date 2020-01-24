<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Fourxxi\RestRequestError\Exception\Model\ErrorObject;
use Fourxxi\RestRequestError\Exception\Model\ErrorTree;

final class ArrayInvalidRequestException extends AbstractInvalidRequestException
{
    /**
     * @var array
     */
    private $errors;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $errors, \Throwable $previous = null)
    {
        parent::__construct($previous);
        $this->errors = $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): ErrorTree
    {
        $errorTree = new ErrorTree();

        foreach ($this->errors as $name => $error) {
            $nestedErrorTree = new ErrorTree();
            $nestedErrorTree->addError(new ErrorObject($error));
            $errorTree->addChildTree($name, $nestedErrorTree);
        }

        return $errorTree;
    }
}
