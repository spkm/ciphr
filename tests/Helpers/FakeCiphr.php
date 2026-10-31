<?php

namespace spkm\Ciphr\Tests\Helpers;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\Ciphr\CiphrConnector;

class FakeCiphr
{
    public const PORTAL = 'example';

    public const BASE_URL = 'https://example.app.ciphr.com/api';

    /**
     * Fake Ciphr: each request class answers with the given response.
     *
     * @param  array<class-string<Request>|string, MockResponse|callable(PendingRequest): MockResponse>  $responses
     */
    public static function respondWith(array $responses = []): MockClient
    {
        MockClient::destroyGlobal();

        return MockClient::global($responses);
    }

    public static function connector(string $portal = self::PORTAL, string $apiKey = 'fake-key'): CiphrConnector
    {
        return new CiphrConnector($portal, $apiKey);
    }

    /**
     * A list of records as Ciphr wraps it: {"PersonDetail": [...]}.
     *
     * @param  list<array<string, mixed>>  $items
     */
    public static function list(string $key, array $items, int $status = 200): MockResponse
    {
        return MockResponse::make([$key => $items], $status);
    }

    /**
     * Ciphr's error body: {"Error": [{"Name": ..., "Description": ...}]}.
     *
     * @param  list<array{Name?: string, Description?: string}>  $errors
     */
    public static function error(array $errors, int $status = 500): MockResponse
    {
        return MockResponse::make(['Error' => $errors], $status);
    }

    /**
     * Send one request through a connector to a faked Ciphr that answers it with the given body.
     *
     * @param  array<mixed>|string  $body
     */
    public static function send(Request $request, array|string $body = [], int $status = 200): Response
    {
        self::respondWith([$request::class => MockResponse::make($body, $status)]);

        return self::connector()->send($request);
    }

    /**
     * The request Ciphr received for the given request class, as Saloon built it.
     *
     * @param  class-string<Request>  $requestClass
     */
    public static function received(MockClient $mockClient, string $requestClass): PendingRequest
    {
        return $mockClient->findResponseByRequest($requestClass)->getPendingRequest();
    }

    /**
     * The query strings Ciphr received for every request of the given class, in the order they were sent.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<array<string, mixed>>
     */
    public static function queriesReceived(MockClient $mockClient, string $requestClass): array
    {
        return collect($mockClient->getRecordedResponses())
            ->filter(fn (Response $response) => $response->getRequest() instanceof $requestClass)
            ->map(fn (Response $response) => $response->getPendingRequest()->query()->all())
            ->values()
            ->all();
    }

    /**
     * The URIs Ciphr received for every request of the given class, as sent on the wire.
     *
     * @param  class-string<Request>  $requestClass
     * @return list<string>
     */
    public static function urisReceived(MockClient $mockClient, string $requestClass): array
    {
        return collect($mockClient->getRecordedResponses())
            ->filter(fn (Response $response) => $response->getRequest() instanceof $requestClass)
            ->map(fn (Response $response) => (string) $response->getPsrRequest()->getUri())
            ->values()
            ->all();
    }
}
