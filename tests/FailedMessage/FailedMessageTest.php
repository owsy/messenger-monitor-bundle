<?php

declare(strict_types=1);

namespace SymfonyCasts\MessengerMonitorBundle\Tests\FailedMessage;

use PHPUnit\Framework\TestCase;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use SymfonyCasts\MessengerMonitorBundle\FailedMessage\FailedMessage;

final class FailedMessageTest extends TestCase
{
    public function testFailedMessageGetters(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $errorDetails = new ErrorDetailsStamp(
            \Exception::class,
            123,
            'The Message',
            new FlattenException(new \Exception()),
        );

        $transportMessage = new TransportMessageIdStamp('id');

        $envelope = (new Envelope($object))
            ->with($errorDetails)
            ->with($transportMessage);

        $failedMessage = new FailedMessage($envelope);

        $this->assertSame('stdClass', $failedMessage->getMessageClass());
        $this->assertSame(['foo' => 'bar'], $failedMessage->getMessageDetails());
        $this->assertSame($errorDetails, $failedMessage->getErrorDetails());
        $this->assertSame($transportMessage, $failedMessage->getTransportMessageId());
    }
}
