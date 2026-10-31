<?php

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use spkm\Ciphr\Exceptions\GoneException;
use spkm\Ciphr\Exceptions\NotFoundException;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Exceptions\UnauthorizedException;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Tests\Fixtures\CreateExampleRequest;
use spkm\Ciphr\Tests\Fixtures\DeleteExampleRequest;
use spkm\Ciphr\Tests\Fixtures\ExampleData;
use spkm\Ciphr\Tests\Fixtures\ExampleResource;
use spkm\Ciphr\Tests\Fixtures\GetExampleRequest;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Fixtures\UpdateExampleRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

function examples(): ExampleResource
{
    return new ExampleResource(FakeCiphr::connector());
}

it('gets every matching record in one request', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [['ID' => 1, 'Name' => 'One'], ['ID' => 2, 'Name' => 'Two']])]);

    $items = examples()
        ->where('Name', 'c', 'o')
        ->whereNot('Active', false)
        ->whereIn('ID', [1, 2, 3])
        ->whereNotIn('Hours', [0])
        ->whereBetween('StartDate', ['2020-01-01', '2020-12-31'])
        ->whereNull('EndDate')
        ->whereNotNull('Name2')
        ->whereContains('A', 'x')->whereNotContains('B', 'y')->whereStartsWith('C', 'z', caseSensitive: true)->whereEndsWith('D', 'w')
        ->orWhere('Name', 'Two')
        ->search('o')
        ->changesFrom('2024-01-01')
        ->orderByDesc('Name')
        ->select('ID', 'Name')
        ->get();

    expect($items)->toBeInstanceOf(Collection::class)->toHaveCount(2)
        ->and($items[1])->toBeInstanceOf(ExampleData::class)
        ->and($items[1]->Name)->toBe('Two')
        ->and(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class))->toBe([[
            'Name' => '[c]o,[eq]Two',
            'Active' => '[ne]false',
            'ID' => '[in]1,2,3',
            'Hours' => '[nin]0',
            'StartDate' => '[range]2020-01-01[to]2020-12-31',
            'EndDate' => '[null]',
            'Name2' => '[notnull]',
            'A' => '[c]x',
            'B' => '[nc]y',
            'C' => '[sw]"z"',
            'D' => '[ew]w',
            'q' => 'o',
            's' => '-Name',
            'f' => 'ID,Name',
            'GetChangesFrom' => '2024-01-01',
        ]]);
});

it('refuses a second where on the same property, and takes two-argument wheres', function () {
    expect(fn () => examples()->where('Name', 'One')->where('Name', 'Two'))->toThrow(LogicException::class, 'use orWhere()')
        ->and(examples()->where('Name', 'One')->where('Gone', null)->orWhere('Name', null)->toArray())->toBe(['Name' => '[eq]One,[null]', 'Gone' => '[null]']);
});

it('sorts by the default key when limiting, paging, first or last need one', function () {
    expect(examples()->limit(5)->toArray())->toBe(['s' => '+ID', 'l' => 5])
        ->and(examples()->forPage(3, 20)->toArray())->toBe(['s' => '+ID', 'p' => 3, 'l' => 20])
        ->and(examples()->orderBy('Name')->limit(5)->toArray())->toBe(['s' => '+Name', 'l' => 5])
        ->and(examples()->toArray())->toBe([])
        ->and(examples()->toQuery())->toBeInstanceOf(Query::class)
        ->and(examples()->limit(1)->toRequest())->toBeInstanceOf(GetExamplesRequest::class);
});

it('fetches the first and last record with the flag and a sort', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => function (PendingRequest $request) {
        return $request->query()->get('first') !== null ? FakeCiphr::list('Example', [['ID' => 1]]) : MockResponse::make(['ID' => 9]);
    }]);

    expect(examples()->first()?->ID)->toBe(1)
        ->and(examples()->orderByDesc('Name')->last()?->ID)->toBe(9)
        ->and(FakeCiphr::urisReceived($mockClient, GetExamplesRequest::class))->toBe([
            FakeCiphr::BASE_URL.'/Example?s=%2BID&first',
            FakeCiphr::BASE_URL.'/Example?s=-Name&last',
        ]);

    FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [])]);
    expect(examples()->first())->toBeNull();
});

it('counts the matching records', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => MockResponse::make('42')]);

    expect(examples()->where('Active', true)->count())->toBe(42)
        ->and(FakeCiphr::urisReceived($mockClient, GetExamplesRequest::class))->toBe([FakeCiphr::BASE_URL.'/Example?Active=%5Beq%5Dtrue&count']);

    FakeCiphr::respondWith([GetExamplesRequest::class => FakeCiphr::list('Example', [['ID' => 1]])]);
    expect(fn () => examples()->count())->toThrow(UnexpectedValueException::class, 'is not a count');
});

it('finds a record by id, or null when Ciphr has none', function () {
    FakeCiphr::respondWith([GetExampleRequest::class => fn (PendingRequest $request) => str_ends_with($request->getUrl(), '/7') ? MockResponse::make(['ID' => 7]) : MockResponse::make('', 404)]);

    expect(examples()->find(7)?->ID)->toBe(7)
        ->and(examples()->find(8))->toBeNull()
        ->and(examples()->findOrFail(7)->ID)->toBe(7)
        ->and(fn () => examples()->findOrFail(8))->toThrow(NotFoundException::class);
});

it('finds nothing when Ciphr answers a missing id with its 500', function () {
    FakeCiphr::respondWith([GetExampleRequest::class => MockResponse::make(['Message' => 'We have experienced a problem, please retry.', 'Error' => []], 500)]);

    expect(examples()->find(8))->toBeNull()
        ->and(fn () => examples()->findOrFail(8))->toThrow(NotFoundException::class);
});

it('finds a record by filtering when the entity has no item end node', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => fn (PendingRequest $request) => FakeCiphr::list('Example', $request->query()->get('ID') === '[eq]7' ? [['ID' => 7]] : [])]);
    $resource = new ExampleResource(FakeCiphr::connector(), hasItemRequest: false);

    expect($resource->find(7)?->ID)->toBe(7)
        ->and($resource->find(8))->toBeNull()
        ->and($resource->findOrFail(7)->ID)->toBe(7)
        ->and(fn () => $resource->findOrFail(8))->toThrow(NotFoundException::class, 'GET /api/Example')
        ->and(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class)[0])->toBe(['ID' => '[eq]7', 's' => '+ID', 'first' => '']);
});

it('pages lazily through every matching record', function () {
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => function (PendingRequest $request) {
        $page = (int) $request->query()->get('p');

        return FakeCiphr::list('Example', $page <= 2 ? [['ID' => $page * 10], ['ID' => $page * 10 + 1]] : []);
    }]);

    $items = examples()->where('Active', true)->orderBy('Name')->lazy(2);

    expect($items)->toBeInstanceOf(LazyCollection::class)
        ->and($items->map(fn (ExampleData $item) => $item->ID)->all())->toBe([10, 11, 20, 21])
        ->and(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class)[0])->toBe(['Active' => '[eq]true', 's' => '+Name', 'p' => 1, 'l' => 2])
        ->and(examples()->cursor()->count())->toBe(4)
        ->and(examples()->paginate(2)->collect()->count())->toBe(4);
});

it('throws the Ciphr exception for an error while paging', function () {
    FakeCiphr::respondWith([GetExamplesRequest::class => MockResponse::make('', 401)]);

    expect(fn () => examples()->lazy()->all())->toThrow(UnauthorizedException::class);
});

it('throws the Ciphr exception for an error on get, first, count and find', function () {
    FakeCiphr::respondWith([GetExamplesRequest::class => MockResponse::make('', 503), GetExampleRequest::class => MockResponse::make('', 503)]);

    expect(fn () => examples()->get())->toThrow(ServerErrorException::class)
        ->and(fn () => examples()->first())->toThrow(ServerErrorException::class)
        ->and(fn () => examples()->count())->toThrow(ServerErrorException::class)
        ->and(fn () => examples()->find(1))->toThrow(ServerErrorException::class);
});

it('creates, updates and deletes records where Ciphr allows', function () {
    $mockClient = FakeCiphr::respondWith([
        CreateExampleRequest::class => MockResponse::make(['ID' => 9, 'Name' => 'New'], 201),
        UpdateExampleRequest::class => MockResponse::make('', 204),
        DeleteExampleRequest::class => MockResponse::make('', 204),
    ]);

    expect(examples()->create(['Name' => 'New'])?->ID)->toBe(9)
        ->and(examples()->update(9, new ExampleData(Name: 'Renamed')))->toBeNull()
        ->and(examples()->delete(9))->toBeTrue()
        ->and(FakeCiphr::received($mockClient, UpdateExampleRequest::class)->body()?->all())->toBe(['ID' => 9, 'Name' => 'Renamed']);

    FakeCiphr::respondWith([DeleteExampleRequest::class => MockResponse::make('', 410)]);
    expect(fn () => examples()->delete(9))->toThrow(GoneException::class);
});

it('refuses writes Ciphr does not document', function () {
    $resource = new ExampleResource(FakeCiphr::connector(), writable: false);

    expect(fn () => $resource->create(['Name' => 'x']))->toThrow(LogicException::class, 'no way to create Example records')
        ->and(fn () => $resource->update(1, ['Name' => 'x']))->toThrow(LogicException::class, 'no way to update Example records')
        ->and(fn () => $resource->delete(1))->toThrow(LogicException::class, 'no way to delete Example records');
});
