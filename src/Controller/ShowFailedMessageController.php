<?php

declare(strict_types=1);

namespace SymfonyCasts\MessengerMonitorBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SymfonyCasts\MessengerMonitorBundle\FailedMessage\FailedMessage;
use SymfonyCasts\MessengerMonitorBundle\FailureReceiver\FailureReceiverProvider;
use Twig\Environment;

/**
 * @internal
 */
final class ShowFailedMessageController
{
    public function __construct(
        private Environment $twig,
        private FailureReceiverProvider $failureReceiverProvider,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $failureReceiver = $this->failureReceiverProvider->getFailureReceiver();
        $envelope = $failureReceiver->find($id);

        if (null === $envelope) {
            throw new NotFoundHttpException();
        }

        return new Response(
            $this->twig->render(
                '@SymfonyCastsMessengerMonitor/failed_message.html.twig',
                [
                    'failed_message' => new FailedMessage($envelope),
                ]
            )
        );
    }
}
