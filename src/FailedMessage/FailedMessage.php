<?php

declare(strict_types=1);

namespace SymfonyCasts\MessengerMonitorBundle\FailedMessage;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;

/**
 * @internal
 */
final class FailedMessage
{
    public function __construct(
        private Envelope $envelope,
    ) {
    }

    public function getTransportMessageId(): StampInterface
    {
        return $this->getStamp(TransportMessageIdStamp::class);
    }

    public function getErrorDetails(): StampInterface
    {
        return $this->getStamp(ErrorDetailsStamp::class);
    }

    public function getMessageClass(): string
    {
        return $this->getMessage()::class;
    }

    public function getMessageDetails(): array
    {
        $details = [];

        foreach ($this->getMessage() as $key => $value) {
            $details[$key] = $value;
        }

        return $details;
    }

    private function getMessage(): object
    {
        return $this->envelope->getMessage();
    }

    /** @param class-string<StampInterface> $stampClass */
    private function getStamp(string $stampClass): StampInterface
    {
        $stamp = $this->envelope->last($stampClass);

        if (null === $stamp) {
            throw new \Exception('Could not find expected stamp: '.$stampClass);
        }

        return $stamp;
    }
}
