<?php

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\Ciphr\Data\Data;

$models = collect(glob(__DIR__.'/../../src/Data/*.php'))
    ->map(fn (string $file) => 'spkm\\Ciphr\\Data\\'.basename($file, '.php'))
    ->reject(fn (string $class) => $class === Data::class)
    ->mapWithKeys(fn (string $class) => [class_basename($class) => [$class]])
    ->all();

it('builds every model from Ciphr\'s values and serialises it back', function (string $class) {
    $attributes = [];
    $expected = [];

    foreach ((new ReflectionClass($class))->getConstructor()->getParameters() as $parameter) {
        $type = $parameter->getType();
        $name = $parameter->getName();
        expect($type)->toBeInstanceOf(ReflectionNamedType::class)
            ->and($parameter->isDefaultValueAvailable())->toBeTrue()
            ->and($name)->toMatch('/^[A-Za-z]\w*$/');

        [$attributes[$name], $expected[$name]] = match ($type->getName()) {
            'int' => ['12', 12],
            'float' => ['1.5', 1.5],
            'bool' => ['True', true],
            'string' => [7, '7'],
            CarbonImmutable::class => ['2020-01-02T00:00:00', '2020-01-02T00:00:00'],
            Collection::class => [[['ID' => 1]], [['ID' => 1]]],
            default => throw new LogicException("$class::$name has an unexpected type"),
        };
    }

    $model = $class::from($attributes);

    expect($model)->toBeInstanceOf(Data::class)
        ->and($model->toArray())->toBe($expected)
        ->and($class::from([]))->toBeInstanceOf($class);
})->with($models);

it('has a model for every entity, with the property its lists are sorted by', function () use ($models) {
    expect($models)->toHaveCount(24);

    foreach (require __DIR__.'/endpoints.php' as $name => [$class, , , , $shape, $dto, , $sortKey]) {
        if ($shape === 'list' && $sortKey !== null) {
            expect(property_exists($dto, $sortKey))->toBeTrue("$name sorts by $sortKey, which ".class_basename($dto).' does not have');
        }
    }
});
