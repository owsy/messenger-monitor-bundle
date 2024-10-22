<?php

declare(strict_types=1);

namespace SymfonyCasts\MessengerMonitorBundle\Tests\FunctionalTests;

use Symfony\Component\HttpFoundation\Response;

final class ShowFailedMessageControllerTest extends AbstractFunctionalTests
{
    public function testShowFailedMessage(): void
    {
        $envelope = $this->dispatchMessage(true);
        $this->handleMessage($envelope, 'queue');

        $crawler = $this->client->request('GET', sprintf('/failed-message/%s', $id = $this->getLastFailedMessageId()));
        self::assertResponseIsSuccessful();

        $this->assertSame('0 Exception', $crawler->filter('h1')->text());
        $this->assertSame('oops!', $crawler->filter('p.lead')->text());

        $this->assertStringContainsString('SymfonyCasts\MessengerMonitorBundle\Tests\TestableMessage', $crawler->filter('.failed-message-details-card .card-header')->text());
        $this->assertStringContainsString('willFail:', $crawler->filter('.failed-message-details-card .card-body')->text());

        $this->assertStringContainsString('Stack trace', $crawler->filter('.failed-message-stack-trace-card .card-header')->text());
        $this->assertStringContainsString('HandleMessageMiddleware', $crawler->filter('.failed-message-stack-trace-card .card-body')->text());
    }

    public function testShowFailedMessageNotFound(): void
    {
        $this->client->request('GET', '/failed-message/123456');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
