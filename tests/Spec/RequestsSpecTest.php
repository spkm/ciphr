<?php

use Saloon\Contracts\Body\HasBody;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Requests\CiphrRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

$endpoints = require __DIR__.'/endpoints.php';

it('sends the method and path Ciphr documents, and maps the response', function (string $class, array $args, string $method, string $path, string $shape, ?string $dto, string $key, ?string $sortKey, bool $hasBody, bool $paginatable) {
    /** @var CiphrRequest $request */
    $request = new $class(...$args);

    expect($request)->toBeInstanceOf(CiphrRequest::class)
        ->and($request->getMethod()->value)->toBe($method)
        ->and($request->resolveEndpoint())->toBe($path)
        ->and($request->dtoClass())->toBe($dto)
        ->and($request->resultKey)->toBe($key)
        ->and($request instanceof Paginatable)->toBe($paginatable)
        ->and($request instanceof HasBody)->toBe($hasBody)
        ->and($request->isSafe())->toBe($method === 'GET');

    if ($request instanceof ListRequest) {
        expect($request->sortKey)->toBe($sortKey);
    }

    $sample = ['ID' => 7, 'Notes' => 'x'];
    $result = match ($shape) {
        'list' => FakeCiphr::send($request, [$key => [$sample]])->dto(),
        'one' => FakeCiphr::send($request, $sample)->dto(),
        default => FakeCiphr::send($request, '', 204)->dto(),
    };

    match ($shape) {
        'list' => expect($result)->toHaveCount(1)->and($result->first())->toBeInstanceOf($dto),
        'one' => expect($result)->toBeInstanceOf($dto)->and($result)->toBeInstanceOf(Data::class),
        default => expect($result)->toBeNull(),
    };
})->with($endpoints);

it('lists every request class once', function () use ($endpoints) {
    $classes = collect(glob(__DIR__.'/../../src/Requests/*/*Request.php'))
        ->map(fn (string $file) => 'spkm\\Ciphr\\Requests\\'.basename(dirname($file)).'\\'.basename($file, '.php'))
        ->sort()
        ->values();
    $listed = collect($endpoints)->map(fn (array $row) => $row[0])->sort()->values();

    expect($listed->all())->toBe($classes->all())
        ->and($listed->unique())->toHaveCount($listed->count())
        ->and($classes)->toHaveCount(51);
});

it('documents every request in ENDPOINTS.md', function () use ($endpoints) {
    $markdown = file_get_contents(__DIR__.'/../../ENDPOINTS.md');

    foreach ($endpoints as $row) {
        expect($markdown)->toContain('`'.str_replace('spkm\\Ciphr\\Requests\\', '', $row[0]).'`');
    }
});
