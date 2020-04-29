<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
        if (!$exception instanceof HttpExceptionInterface) {
            return;
        }

        $response = new JsonResponse(
            $this->serializer->serialize($this->normalizer->normalize($exception), 'json'),
            $exception->getStatusCode(),
            [],
            true
        );

        $event->setResponse($response);
    }
}