<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ExceptionResponseListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if (!$exception instanceof HttpExceptionInterface) {
            return;
        }

        $response->setStatusCode($exception->getStatusCode());
        $response->setJson($this->serializer->serialize($exception, 'json'));
        $event->setResponse($response);
    }
}