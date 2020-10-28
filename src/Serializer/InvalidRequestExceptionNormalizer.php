<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Serializer;

use Fourxxi\RestRequestError\Exception\InvalidRequestExceptionInterface;
use Fourxxi\RestRequestError\Factory\View\ErrorViewFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class InvalidRequestExceptionNormalizer implements NormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $objectNormalizer;

    /**
     * @var ErrorViewFactory
     */
    private $errorViewFactory;

    public function __construct(ErrorViewFactory $errorViewFactory, ObjectNormalizer $normalizer)
    {
        $this->errorViewFactory = $errorViewFactory;
        $this->objectNormalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     *
     * @param InvalidRequestExceptionInterface $object
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $errorView = $this->errorViewFactory->createFromErrorTree($object->getErrors());

        return $this->objectNormalizer->normalize($errorView, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof InvalidRequestExceptionInterface;
    }
}
