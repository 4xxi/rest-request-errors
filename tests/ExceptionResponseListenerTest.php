<?php

declare(strict_types=1);

namespace Fourxxi\RestRequestError\Tests;

use Fourxxi\RestRequestError\EventListener\ExceptionResponseListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    private $serializator;

    public function setUp(): void
    {
        $this->serializator = $this->createMock(SerializerInterface::class);
        $this->listener = new ExceptionResponseListener($this->serializator);
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
    public function exceptionResponseListenerSerializesExceptionAndSetStatusCode(): void
    {
        $exception = new HttpException(200, 'test');
        $event = $this->createExceptionEvent($exception);

        $this->serializator->expects($this->once())->method('serialize')->with($exception, 'json');
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