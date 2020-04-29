<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Tests;

use Fourxxi\RestRequestError\EventListener\ExceptionResponseListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    public function exceptionResponseListenerHandlingOnlyHttpExceptions(): void
    {
        $event = $this->createExceptionEvent(new \Exception());
        $event->expects($this->never())->method('setResponse');

        $this->listener->onKernelException($event);
    }

    /**
     * @test
     * @covers \Fourxxi\RestRequestError\EventListener\ExceptionResponseListener
     */
    public function exceptionResponseListenerSerializesException(): void
    {
        $this->serializer->method('serialize')->willReturn('[]');
        $exception = new HttpException(200, 'test');
        $event = $this->createExceptionEvent($exception);

        $this->normalizer->expects($this->once())->method('normalize')->with($exception);
        $this->serializer->expects($this->once())->method('serialize');
        $this->listener->onKernelException($event);
    }

    /**
     * @return ExceptionEvent|MockObject
     */
    private function createExceptionEvent(\Exception $exception)
    {
        $mock = $this->createMock(ExceptionEvent::class);
        $mock->method('getThrowable')->willReturn($exception);

        return $mock;
    }
}