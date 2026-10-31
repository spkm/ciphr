<?php

use Carbon\CarbonImmutable;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Tests\Fixtures\ExampleData;
use spkm\Ciphr\Tests\Fixtures\ExampleDetail;

it('casts Ciphr\'s values to the property types', function () {
    $data = ExampleData::from(['ID' => '12', 'Name' => 7, 'Active' => 'True', 'Hours' => '1500.50', 'StartDate' => '2018-10-12T00:00:00', 'Unknown' => 'ignored']);

    expect($data->ID)->toBe(12)
        ->and($data->Name)->toBe('7')
        ->and($data->Active)->toBeTrue()
        ->and($data->Hours)->toBe(1500.5)
        ->and($data->StartDate)->toBeInstanceOf(CarbonImmutable::class)
        ->and($data->StartDate?->toDateString())->toBe('2018-10-12');
});

it('treats Ciphr\'s empty date and unusable values as null', function () {
    $data = ExampleData::from(['ID' => 'x', 'Active' => 'maybe', 'Hours' => [], 'StartDate' => '0001-01-01T00:00:00']);

    expect($data->ID)->toBeNull()->and($data->Active)->toBeNull()->and($data->Hours)->toBeNull()->and($data->StartDate)->toBeNull()
        ->and(ExampleData::from(['StartDate' => 'not a date'])->StartDate)->toBeNull()
        ->and(ExampleData::from(['StartDate' => '12/10/2018'])->StartDate?->toDateString())->toBe('2018-10-12');
});

it('maps a nested list to its models', function () {
    $data = ExampleData::from(['ID' => 1, 'Details' => [['ID' => 2, 'Days' => '1.5'], ['ID' => 3]]]);

    expect($data->Details)->toHaveCount(2)
        ->and($data->Details[0])->toBeInstanceOf(ExampleDetail::class)
        ->and($data->Details[0]->Days)->toBe(1.5)
        ->and(ExampleData::from([])->Details)->toBeEmpty();
});

it('serialises back to Ciphr\'s keys, leaving out what is unset', function () {
    $data = new ExampleData(ID: 1, Name: 'One', Active: false, StartDate: CarbonImmutable::parse('2020-01-02 03:04:05'));

    expect($data->toArray())->toBe(['ID' => 1, 'Name' => 'One', 'Active' => false, 'StartDate' => '2020-01-02T03:04:05'])
        ->and(json_encode($data))->toBe('{"ID":1,"Name":"One","Active":false,"StartDate":"2020-01-02T03:04:05"}')
        ->and(ExampleData::from(['ID' => 1, 'Details' => [['ID' => 2]]])->toArray())->toBe(['ID' => 1, 'Details' => [['ID' => 2]]]);
});

it('collects records into models', function () {
    $items = ExampleData::collect([['ID' => 1], ['ID' => 2]]);

    expect($items)->toHaveCount(2)->and($items[1])->toBeInstanceOf(Data::class)->and($items[1]->ID)->toBe(2)
        ->and(ExampleData::collect(null))->toBeEmpty();
});

it('insists on a value for a required property', function () {
    $required = new readonly class(Name: 'x') extends Data
    {
        public function __construct(public string $Name) {}
    };

    expect(fn () => $required::from([]))->toThrow(InvalidArgumentException::class, 'needs a value for "Name"');
});
