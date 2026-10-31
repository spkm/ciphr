<?php

use Carbon\CarbonImmutable;
use spkm\Ciphr\Query\Operator;
use spkm\Ciphr\Query\Query;

enum Colour: string
{
    case Red = 'red';
}

it('compiles each condition to Ciphr\'s grammar', function (string|Operator $operator, mixed $value, string $expected) {
    expect(Query::make()->where('Prop', $operator, $value)->toArray())->toBe(['Prop' => $expected]);
})->with([
    'eq' => ['=', 'Smith', '[eq]Smith'],
    'eq by code' => ['eq', 'Smith', '[eq]Smith'],
    'eq by enum' => [Operator::Equal, 'Smith', '[eq]Smith'],
    'ne' => ['!=', 'Smith', '[ne]Smith'],
    'ne diamond' => ['<>', 'Smith', '[ne]Smith'],
    'gt' => ['>', 25, '[gt]25'],
    'ge' => ['>=', 30, '[ge]30'],
    'lt' => ['<', 25, '[lt]25'],
    'le' => ['<=', 30, '[le]30'],
    'in' => ['in', ['Smith', 'Jones'], '[in]Smith,Jones'],
    'in single' => ['in', 'Smith', '[in]Smith'],
    'nin' => ['not in', [20, 25], '[nin]20,25'],
    'range' => ['between', [10, 20], '[range]10[to]20'],
    'sw' => ['starts with', 'Sam', '[sw]Sam'],
    'ew' => ['ew', 'Drive', '[ew]Drive'],
    'c' => ['contains', 'Crescent', '[c]Crescent'],
    'nc' => ['nc', 'Crescent', '[nc]Crescent'],
    'null' => ['null', null, '[null]'],
    'notnull' => ['is not null', null, '[notnull]'],
    'bool' => ['=', true, '[eq]true'],
    'false' => ['=', false, '[eq]false'],
    'float' => ['>', 2.5, '[gt]2.5'],
    'enum value' => ['=', Colour::Red, '[eq]red'],
    'date' => ['>=', CarbonImmutable::parse('2020-01-01'), '[ge]2020-01-01'],
    'date and time' => ['>=', CarbonImmutable::parse('2020-01-01 09:30:00'), '[ge]2020-01-01T09:30:00'],
    'date range' => ['range', [CarbonImmutable::parse('2000-01-01'), CarbonImmutable::parse('2000-12-31')], '[range]2000-01-01[to]2000-12-31'],
]);

it('quotes text for a case-sensitive match', function () {
    expect(Query::make()->where('Surname', '=', 'Smith', caseSensitive: true)->toArray())->toBe(['Surname' => '[eq]"Smith"'])
        ->and(Query::make()->where('Surname', 'in', ['Smith', 'Jones'], caseSensitive: true)->toArray())->toBe(['Surname' => '[in]"Smith","Jones"'])
        ->and(Query::make()->where('HolTaken', '>', 25, caseSensitive: true)->toArray())->toBe(['HolTaken' => '[gt]25']);
});

it('ORs conditions on one property and ANDs conditions on different ones', function () {
    $query = Query::make()
        ->where('Name', 'c', 'Mark')
        ->where('DateLeft', 'range', ['2000-01-01', '2000-12-31'])
        ->where('DateLeft', '>=', '2010-01-01');

    expect($query->toArray())->toBe(['Name' => '[c]Mark', 'DateLeft' => '[range]2000-01-01[to]2000-12-31,[ge]2010-01-01'])
        ->and($query->hasCondition('DateLeft'))->toBeTrue()
        ->and($query->hasCondition('Surname'))->toBeFalse();
});

it('rejects what Ciphr cannot read', function (callable $build, string $message) {
    expect($build)->toThrow(InvalidArgumentException::class, $message);
})->with([
    'unknown operator' => [fn () => Query::make()->where('Prop', 'like', 'x'), 'no "like" condition'],
    'comma in value' => [fn () => Query::make()->where('Prop', '=', 'a,b'), 'cannot contain a comma'],
    'bad property' => [fn () => Query::make()->where('Prop Name', '=', 'x'), 'not a Ciphr property name'],
    'bad sort property' => [fn () => Query::make()->orderBy('x y'), 'not a Ciphr property name'],
    'bad direction' => [fn () => Query::make()->orderBy('Name', 'up'), '"asc" or "desc"'],
    'null value' => [fn () => Query::make()->where('Prop', '=', null), 'use whereNull()'],
    'empty in' => [fn () => Query::make()->where('Prop', 'in', []), 'at least one value'],
    'range of one' => [fn () => Query::make()->where('Prop', 'range', [1]), 'two values'],
    'object value' => [fn () => Query::make()->where('Prop', '=', new stdClass), 'must be a string, number, bool, date or enum'],
    'page zero' => [fn () => Query::make()->page(0), 'start at 1'],
    'zero limit' => [fn () => Query::make()->limit(0), 'at least 1'],
]);

it('sorts, selects, searches and pages', function () {
    $query = Query::make()->orderBy('Surname')->orderBy('DateLeft', 'desc')->select('Name', 'JobTitle')->select('Name')->search('Smith')->page(2)->limit(10);

    expect($query->toArray())->toBe(['q' => 'Smith', 's' => '+Surname,-DateLeft', 'f' => 'Name,JobTitle', 'p' => 2, 'l' => 10])
        ->and(Query::make()->search('Smith', caseSensitive: true)->toArray())->toBe(['q' => '"Smith"']);
});

it('sends first, last and count as bare flags', function () {
    expect(Query::make()->first()->toArray())->toBe(['first' => ''])
        ->and(Query::make()->last()->toArray())->toBe(['last' => ''])
        ->and(Query::make()->count()->toArray())->toBe(['count' => ''])
        ->and(Query::make()->first()->count()->flagged())->toBe('count')
        ->and((string) Query::make()->where('Name', 'c', 'Mark')->orderBy('ID')->count())->toBe('Name=[c]Mark&s=+ID&count');
});

it('asks for changes since a date, since today or between dates', function () {
    expect(Query::make()->changesFrom()->toArray())->toBe(['GetChangesFrom' => ''])
        ->and(Query::make()->changesFrom('2019-01-01')->toArray())->toBe(['GetChangesFrom' => '2019-01-01'])
        ->and(Query::make()->changesFrom(CarbonImmutable::parse('2019-01-01'), CarbonImmutable::parse('2019-02-28'))->toArray())->toBe(['GetChangesFrom' => '2019-01-01[to]2019-02-28']);
});

it('adds a default sort only when Ciphr needs one', function () {
    expect(Query::make()->withDefaultSort('ID')->toArray())->toBe([])
        ->and(Query::make()->limit(5)->withDefaultSort('ID')->toArray())->toBe(['s' => '+ID', 'l' => 5])
        ->and(Query::make()->page(2)->withDefaultSort('ID')->needsSort())->toBeTrue()
        ->and(Query::make()->first()->withDefaultSort('ID')->toArray())->toBe(['s' => '+ID', 'first' => ''])
        ->and(Query::make()->count()->withDefaultSort('ID')->toArray())->toBe(['count' => ''])
        ->and(Query::make()->limit(5)->orderBy('Name', 'desc')->withDefaultSort('ID')->toArray())->toBe(['s' => '-Name', 'l' => 5]);
});

it('is immutable', function () {
    $query = Query::make();
    $query->where('Name', '=', 'x');
    $query->orderBy('Name');
    $query->limit(1);

    expect($query->toArray())->toBe([]);
});
