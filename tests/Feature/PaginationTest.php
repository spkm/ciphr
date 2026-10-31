<?php

use Illuminate\Support\LazyCollection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\AdhocListRequest;
use spkm\Ciphr\Tests\Fixtures\ExampleData;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

function examplePages(int $pages, int $perPage = 100): Closure
{
    return function (PendingRequest $request) use ($pages, $perPage) {
        $page = (int) $request->query()->get('p');
        $items = $page <= $pages ? array_map(fn (int $i) => ['ID' => ($page - 1) * $perPage + $i], range(1, $perPage)) : [];

        return FakeCiphr::list('Example', $items);
    };
}

it('asks for each page in turn, sorted, until an empty one', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => examplePages(2)]);

    $items = FakeCiphr::connector()->paginate(new GetExamplesRequest)->collect();
    $all = $items->all();

    expect($items)->toBeInstanceOf(LazyCollection::class)
        ->and($all)->toHaveCount(200)
        ->and(end($all))->toBe(['ID' => 200])
        ->and(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class))->toBe([
            ['p' => 1, 'l' => 100, 's' => '+ID'],
            ['p' => 2, 'l' => 100, 's' => '+ID'],
            ['p' => 3, 'l' => 100, 's' => '+ID'],
        ]);
});

it('keeps the sort the query gives and the request\'s own sort key otherwise', function () {
    $mockClient = FakeCiphr::respondWith([
        GetExamplesRequest::class => examplePages(1, 2),
        AdhocListRequest::class => fn () => FakeCiphr::list('Thing', []),
    ]);
    $connector = FakeCiphr::connector();

    $connector->paginate(new GetExamplesRequest(Query::make()->orderBy('Name', 'desc')), 2)->collect()->all();
    $connector->paginate(new AdhocListRequest('/Thing', 'Thing', 'ThingID'), 50)->collect()->all();

    expect(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class)[0])->toBe(['s' => '-Name', 'p' => 1, 'l' => 2])
        ->and(FakeCiphr::queriesReceived($mockClient, AdhocListRequest::class))->toBe([['p' => 1, 'l' => 50, 's' => '+ThingID']]);
});

it('fetches a page only when the loop reaches it', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => examplePages(3)]);

    $first = FakeCiphr::connector()->paginate(new GetExamplesRequest)->dtos()->first();

    expect($first)->toBeInstanceOf(ExampleData::class)
        ->and($first->ID)->toBe(1)
        ->and($mockClient->getRecordedResponses())->toHaveCount(1);
});

it('takes the page size from config', function () {
    config()->set('ciphr.pagination.per_page', 25);
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => examplePages(1, 25)]);

    FakeCiphr::connector()->paginate(new GetExamplesRequest)->collect()->all();

    expect(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class)[0]['l'])->toBe(25);
});

it('refuses to give models for a request without one, and to page asynchronously', function () {
    FakeCiphr::respondWith([AdhocListRequest::class => fn () => FakeCiphr::list('Thing', [])]);
    $connector = FakeCiphr::connector();

    expect(fn () => $connector->paginate(new AdhocListRequest('/Thing'))->dtos())->toThrow(LogicException::class, 'has no model')
        ->and(fn () => $connector->paginate(new AdhocListRequest('/Thing'))->async()->collect()->all())->toThrow(LogicException::class, 'asynchronously');
});

it('treats a page that is not JSON as a server error', function () {
    FakeCiphr::respondWith([GetExamplesRequest::class => MockResponse::make('<html></html>')]);

    expect(fn () => FakeCiphr::connector()->paginate(new GetExamplesRequest)->collect()->all())
        ->toThrow(ServerErrorException::class);
});
