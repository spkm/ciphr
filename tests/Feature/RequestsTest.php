<?php

use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\AdhocListRequest;
use spkm\Ciphr\Tests\Fixtures\CreateExampleRequest;
use spkm\Ciphr\Tests\Fixtures\DeleteExampleRequest;
use spkm\Ciphr\Tests\Fixtures\ExampleData;
use spkm\Ciphr\Tests\Fixtures\GetExampleRequest;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Fixtures\UpdateExampleRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

it('maps a list to models whichever way Ciphr wraps it', function (array|string $body) {
    $items = FakeCiphr::send(new GetExamplesRequest, $body)->dto();

    expect($items)->toBeInstanceOf(Collection::class)->toHaveCount(2)
        ->and($items[0])->toBeInstanceOf(ExampleData::class)
        ->and($items[0]->ID)->toBe(1)
        ->and($items[1]->Name)->toBe('Two');
})->with([
    'wrapped in the entity name' => [['Example' => [['ID' => 1, 'Name' => 'One'], ['ID' => 2, 'Name' => 'Two']]]],
    'wrapped in another name' => [['Examples' => [['ID' => 1, 'Name' => 'One'], ['ID' => 2, 'Name' => 'Two']]]],
    'bare list' => [[['ID' => 1, 'Name' => 'One'], ['ID' => 2, 'Name' => 'Two']]],
]);

it('maps an empty list, a bare object and a wrapped object', function () {
    expect(FakeCiphr::send(new GetExamplesRequest, '')->dto())->toBeEmpty()
        ->and(FakeCiphr::send(new GetExamplesRequest, ['Example' => []])->dto())->toBeEmpty()
        ->and(FakeCiphr::send(new GetExamplesRequest, ['ID' => 7])->dto()->first()->ID)->toBe(7)
        ->and(FakeCiphr::send(new GetExamplesRequest, ['Example' => ['ID' => 7]])->dto()->first()->ID)->toBe(7)
        ->and(FakeCiphr::send(new GetExamplesRequest, [['ID' => 1], 'junk', 3])->dto())->toHaveCount(1);
});

it('reads a count from a list request', function () {
    expect(FakeCiphr::send(new GetExamplesRequest(Query::make()->count()), '12')->dto())->toBe(12)
        ->and(FakeCiphr::send(new GetExamplesRequest(Query::make()->count()), ['Count' => 12])->dto())->toBe(12)
        ->and(FakeCiphr::send(new GetExamplesRequest(Query::make()->count()), ['Count' => '12'])->dto())->toBe(12);
});

it('maps one record, or null when Ciphr sends nothing', function () {
    $one = FakeCiphr::send(new GetExampleRequest(7), ['ID' => 7, 'Name' => 'Seven', 'Active' => 'True', 'Hours' => '7.5', 'StartDate' => '2020-01-02T00:00:00'])->dto();

    expect($one)->toBeInstanceOf(ExampleData::class)
        ->and($one->Name)->toBe('Seven')
        ->and($one->Active)->toBeTrue()
        ->and($one->Hours)->toBe(7.5)
        ->and($one->StartDate?->toDateString())->toBe('2020-01-02')
        ->and(FakeCiphr::send(new GetExampleRequest(7), '')->dto())->toBeNull()
        ->and(FakeCiphr::send(new GetExampleRequest(7), 'null')->dto())->toBeNull()
        ->and(FakeCiphr::send(new GetExampleRequest(7), ['Example' => ['ID' => 7]])->dto()->ID)->toBe(7);
});

it('treats a successful response that is not JSON as a server error', function () {
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, '<html><title>Maintenance</title></html>')->dto())
        ->toThrow(ServerErrorException::class, "with an HTML page instead of JSON ('Maintenance')");
    expect(fn () => FakeCiphr::send(new GetExampleRequest(1), 'not json')->dto())
        ->toThrow(ServerErrorException::class, 'with a body that is not JSON');
});

it('sends the query, the flags bare and the brackets encoded', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);

    FakeCiphr::connector()->send(new GetExamplesRequest(Query::make()->where('Surname', '=', 'Smith')->orderBy('Surname')->count()->changesFrom()));
    FakeCiphr::connector()->send(new GetExamplesRequest(['Surname' => '[eq]Smith', 'first' => '', 's' => '+ID']));

    expect(FakeCiphr::urisReceived($mockClient, GetExamplesRequest::class))->toBe([
        FakeCiphr::BASE_URL.'/Example?Surname=%5Beq%5DSmith&s=%2BSurname&count&GetChangesFrom',
        FakeCiphr::BASE_URL.'/Example?Surname=%5Beq%5DSmith&first&s=%2BID',
    ]);
});

it('sends a record as JSON, from an array or a model, with the id on an update', function () {
    $mockClient = FakeCiphr::respondWith([
        CreateExampleRequest::class => MockResponse::make(['ID' => 9, 'Name' => 'New'], 201),
        UpdateExampleRequest::class => MockResponse::make('', 204),
        DeleteExampleRequest::class => MockResponse::make('', 204),
    ]);
    $connector = FakeCiphr::connector();

    $created = $connector->send(new CreateExampleRequest(['Name' => 'New']))->dto();
    $updated = $connector->send(new UpdateExampleRequest(9, new ExampleData(Name: 'Renamed', Active: false)))->dto();
    $deleted = $connector->send(new DeleteExampleRequest(9))->dto();

    expect($created)->toBeInstanceOf(ExampleData::class)
        ->and($created->ID)->toBe(9)
        ->and($updated)->toBeNull()
        ->and($deleted)->toBeNull()
        ->and(FakeCiphr::received($mockClient, CreateExampleRequest::class)->body()?->all())->toBe(['Name' => 'New'])
        ->and(FakeCiphr::received($mockClient, UpdateExampleRequest::class)->body()?->all())->toBe(['ID' => 9, 'Name' => 'Renamed', 'Active' => false])
        ->and(FakeCiphr::received($mockClient, CreateExampleRequest::class)->headers()->get('Content-Type'))->toBe('application/json');
});

it('caches a list or item only when caching is on', function () {
    $mockClient = FakeCiphr::respondWith([GetExampleRequest::class => MockResponse::make(['ID' => 1])]);
    $connector = FakeCiphr::connector();

    $connector->send(new GetExampleRequest(1));
    $connector->send(new GetExampleRequest(1));
    expect($mockClient->getRecordedResponses())->toHaveCount(2);

    config()->set('ciphr.cache.enabled', true);
    $connector->send(new GetExampleRequest(1));
    $cached = $connector->send(new GetExampleRequest(1));
    expect($mockClient->getRecordedResponses())->toHaveCount(3)
        ->and($cached->isCached())->toBeTrue();
});

it('queries any end node ad hoc', function () {
    $mockClient = FakeCiphr::respondWith([AdhocListRequest::class => FakeCiphr::list('Thing', [['ID' => 1]])]);

    $items = FakeCiphr::connector()->send(new AdhocListRequest('/Thing', 'Thing', 'ThingID', Query::make()->limit(1)))->dto();

    expect($items->first())->toBe(['ID' => 1])
        ->and(FakeCiphr::queriesReceived($mockClient, AdhocListRequest::class))->toBe([['l' => 1]]);
});
