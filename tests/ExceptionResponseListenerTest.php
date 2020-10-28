<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Tests;

use Fourxxi\RestRequestError\EventListener\ExceptionResponseListener;
use Fourxxi\RestRequestError\Exception\InvalidRequestExceptionInterface;
use Fourxxi\RestRequestError\Exception\Model\ErrorTree;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ExceptionResponseListenerTest extends TestCase
{
    /**
     * @var ExceptionResponseListener
     */
    private $listener;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var NormalizerInterface|MockObject
     */
    private $normalizer;

    public function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->normalizer = $this->createMock(NormalizerInterface::class);
        $this->listener = new ExceptionResponseListener($this->serializer, $this->normalizer);
    }

    /**
     * @test
     * @covers \Fourxxi\RestRequestError\EventListener\ExceptionResponseListener
     */
    public function exceptionResponseListenerHandlingOnlyInvalidRequestExceptions(): void
    {
        $event = $this->createExceptionEvent(new \Exception());

        $this->listener->onKernelException($event);

        $this->assertNull($event->getResponse());
    }

    /**
     * @test
     * @covers \Fourxxi\RestRequestError\EventListener\ExceptionResponseListener
     */
    public function exceptionResponseListenerSerializesException(): void
    {
        $this->serializer->method('serialize')->willReturn('[]');
        $this->normalizer->method('supportsNormalization')->willReturn(true);
        $exception = $this->createMock(InvalidRequestExceptionInterface::class);
        $exception->method('getErrors')->willReturn(new ErrorTree());
        $event = $this->createExceptionEvent($exception);

        $this->normalizer->expects($this->once())->method('normalize')->with($exception);
        $this->serializer->expects($this->once())->method('serialize');
        $this->listener->onKernelException($event);
    }

    /**
     * @return ExceptionEvent|MockObject
     */
    private function createExceptionEvent($exception)
    {
        $kernel = $this->createMock(KernelInterface::class);
        $request = $this->createMock(Request::class);

        return new ExceptionEvent($kernel, $request, HttpKernelInterface::MASTER_REQUEST, $exception);
    }
}