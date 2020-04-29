<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Serializer;

use Fourxxi\RestRequestError\Exception\InvalidRequestExceptionInterface;
use Fourxxi\RestRequestError\Factory\View\ErrorViewFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

final class InvalidRequestExceptionNormalizer implements NormalizerInterface
{
    /**
     * @var ErrorViewFactory
     */
    private $errorViewFactory;

    public function __construct(ErrorViewFactory $errorViewFactory)
    {
        $this->errorViewFactory = $errorViewFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @param InvalidRequestExceptionInterface|BadRequestHttpException $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $errorTree = $object instanceof InvalidRequestExceptionInterface
            ? $object->getErrors()
            : $object->getPrevious()->getErrors();

        $serializer = new Serializer([new ObjectNormalizer()]);

        return $serializer->normalize($this->errorViewFactory->createFromErrorTree($errorTree), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof InvalidRequestExceptionInterface) {
            return true;
        }

        if (!$data instanceof BadRequestHttpException) {
            return false;
        }

        $previous = $data->getPrevious();

        return $previous instanceof InvalidRequestExceptionInterface;
    }
}
