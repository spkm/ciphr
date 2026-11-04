<?php

use Saloon\Enums\Method;
use Saloon\Http\Faking\MockResponse;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Requests\AdhocBodyRequest;
use spkm\Ciphr\Requests\AdhocListRequest;
use spkm\Ciphr\Requests\AdhocRequest;
use spkm\Ciphr\Resources\AdhocResource;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

it('talks to the portal\'s API with the key as an apikey token', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);

    (new CiphrConnector('school', 'secret-key'))->send(new GetExamplesRequest);
    $received = FakeCiphr::received($mockClient, GetExamplesRequest::class);

    expect($received->getUrl())->toBe('https://school.app.ciphr.com/api/Example')
        ->and($received->headers()->get('Authorization'))->toBe('apikey secret-key')
        ->and($received->headers()->get('Accept'))->toBe('application/json')
        ->and($received->headers()->get('Content-Type'))->toBeNull();
});

it('uses a base URL override when given one', function () {
    $connector = new CiphrConnector('school', 'secret-key', 'https://hr.example.test/api');

    expect($connector->resolveBaseUrl())->toBe('https://hr.example.test/api')
        ->and($connector->portal())->toBe('school');
});

it('takes its timeouts from config', function () {
    config()->set('ciphr.timeouts.connect', 3);
    config()->set('ciphr.timeouts.request', 7);
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);

    FakeCiphr::connector()->send(new GetExamplesRequest);
    $config = FakeCiphr::received($mockClient, GetExamplesRequest::class)->config()->all();

    expect($config['connect_timeout'])->toBe(3.0)->and($config['timeout'])->toBe(7.0);
});

it('sends ad-hoc requests to any path', function () {
    $mockClient = FakeCiphr::respondWith([AdhocRequest::class => MockResponse::make(['ok' => true]), AdhocBodyRequest::class => MockResponse::make(['ok' => true])]);
    $connector = FakeCiphr::connector();

    expect($connector->get('/Anything', ['s' => '+ID', 'count' => ''])->dto())->toBe(['ok' => true]);
    $connector->post('/Anything', ['Name' => 'x']);
    $connector->put('/Anything/1', ['Name' => 'y']);
    $connector->delete('/Anything/1');

    // Ciphr's gateway refuses a GET with a body, so only the POST and PUT carry one.
    $sent = collect($mockClient->getRecordedResponses())->map(fn ($r) => [$r->getPendingRequest()->getMethod(), (string) $r->getPsrRequest()->getUri(), (string) $r->getPsrRequest()->getBody()]);

    expect($sent->all())->toEqual([
        [Method::GET, FakeCiphr::BASE_URL.'/Anything?s=%2BID&count', ''],
        [Method::POST, FakeCiphr::BASE_URL.'/Anything', '{"Name":"x"}'],
        [Method::PUT, FakeCiphr::BASE_URL.'/Anything/1', '{"Name":"y"}'],
        [Method::DELETE, FakeCiphr::BASE_URL.'/Anything/1', ''],
    ]);
});

it('queries any end node with the builder', function () {
    $mockClient = FakeCiphr::respondWith([AdhocListRequest::class => FakeCiphr::list('Thing', [['ID' => 1, 'Name' => 'One']])]);

    $builder = FakeCiphr::connector()->from('/Thing');
    $items = $builder->where('Name', 'One')->limit(1)->get();

    expect($builder)->toBeInstanceOf(AdhocResource::class)
        ->and($items->all())->toBe([['ID' => 1, 'Name' => 'One']])
        ->and(FakeCiphr::urisReceived($mockClient, AdhocListRequest::class))->toBe([FakeCiphr::BASE_URL.'/Thing?Name=%5Beq%5DOne&s=%2BID&l=1']);
});
