<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Factory\View;

use Fourxxi\RestRequestError\Exception\Model\ErrorObject;
use Fourxxi\RestRequestError\Exception\Model\ErrorTree;
use Fourxxi\RestRequestError\View\ErrorView;

final class ErrorViewFactory
{
    public function createFromErrorTree(ErrorTree $errorTree): ErrorView
    {
        $view = new ErrorView();

        $view->errors = array_map(static function (ErrorObject $error) {
            return $error->getMessage();
        }, $errorTree->getErrors());

        $view->children = [];

        foreach ($errorTree->getChildren() as $name => $child) {
            $view->children[$name] = $this->createFromErrorTree($child);
        }

        return $view;
    }
}
