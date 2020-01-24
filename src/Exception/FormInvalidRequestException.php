<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Exception;

use Fourxxi\RestRequestError\Exception\Model\ErrorObject;
use Fourxxi\RestRequestError\Exception\Model\ErrorTree;
use Symfony\Component\Form\FormInterface;

final class FormInvalidRequestException extends AbstractInvalidRequestException
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ErrorTree
     */
    private $errors;

    public function __construct(FormInterface $form, \Throwable $previous = null)
    {
        parent::__construct($previous);
        $this->form = $form;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(): ErrorTree
    {
        if (null !== $this->errors) {
            return $this->errors;
        }

        return $this->errors = $this->createErrorsFromForm($this->form);
    }

    private function createErrorsFromForm(FormInterface $form): ErrorTree
    {
        $errorTree = new ErrorTree();

        foreach ($form->getErrors() as $error) {
            $errorTree->addError(new ErrorObject($error->getMessage()));
        }

        foreach ($form->all() as $childForm) {
            $childTree = $this->createErrorsFromForm($childForm);
            if ($childTree->hasChildren() || $childTree->hasErrors()) {
                $errorTree->addChildTree($childForm->getName(), $childTree);
            }
        }

        return $errorTree;
    }
}
