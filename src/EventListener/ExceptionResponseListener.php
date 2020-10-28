<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\EventListener;

use Fourxxi\RestRequestError\Exception\InvalidRequestExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ExceptionResponseListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    public function __construct(SerializerInterface $serializer, NormalizerInterface $normalizer)
    {
        $this->serializer = $serializer;
        $this->normalizer = $normalizer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof InvalidRequestExceptionInterface || !$this->normalizer->supportsNormalization($exception)) {
            return;
        }

        $response = new JsonResponse(
            $this->serializer->serialize($this->normalizer->normalize($exception), 'json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );

        $event->setResponse($response);
    }
}