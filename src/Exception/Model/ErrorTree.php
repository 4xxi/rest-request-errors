<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception\Model;

final class ErrorTree
{
    /**
     * @var ErrorObject[]
     */
    private $errors = [];

    /**
     * @var ErrorTree[]
     */
    private $children = [];

    /**
     * @return ErrorObject[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError(ErrorObject $error): void
    {
        if (false === \in_array($error, $this->errors, true)) {
            $this->errors[] = $error;
        }
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return ErrorTree[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function addChildTree(string $path, ErrorTree $errorTree): void
    {
        $this->children[$path] = $errorTree;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }
}
