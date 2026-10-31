<?php

use Saloon\Http\BaseResource;
use spkm\Ciphr\Facades\Ciphr;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Resources\HasResources;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

$endpoints = require __DIR__.'/endpoints.php';
$listed = array_map(fn (array $row) => $row[0], $endpoints);

function resourceAccessors(): array
{
    return array_map(fn (ReflectionMethod $method) => $method->getName(), (new ReflectionClass(HasResources::class))->getMethods(ReflectionMethod::IS_PUBLIC));
}

it('gives a resource for every entity', function () {
    expect(resourceAccessors())->toHaveCount(22);

    foreach (resourceAccessors() as $accessor) {
        $resource = FakeCiphr::connector()->{$accessor}();

        expect($resource)->toBeInstanceOf($resource instanceof Builder ? Builder::class : BaseResource::class);
    }
});

it('sends a listed request from every builder', function () use ($listed) {
    $connector = FakeCiphr::connector();
    $builders = collect(resourceAccessors())->map(fn (string $accessor) => $connector->{$accessor}())->filter(fn ($resource) => $resource instanceof Builder);
    $builders->push($connector->personDetails()->jobPay(1), $connector->personDetails()->qualifications(1), $connector->absences()->details());

    expect($builders)->toHaveCount(24);

    foreach ($builders as $builder) {
        expect($listed)->toContain($builder->toRequest()::class);
    }
});

it('reaches every resource through the facade', function () {
    config()->set('ciphr.portal', 'school');
    config()->set('ciphr.api_key', 'secret');
    $markdown = file_get_contents(__DIR__.'/../../ENDPOINTS.md');
    $readme = file_get_contents(__DIR__.'/../../README.md');

    foreach (resourceAccessors() as $accessor) {
        expect(Ciphr::{$accessor}())->toBeInstanceOf(FakeCiphr::connector()->{$accessor}()::class)
            ->and($markdown)->toContain("Ciphr::$accessor()")
            ->and($readme)->toContain("$accessor()");
    }
});
