![Banner](https://banners.beyondco.de/Ciphr.png?theme=dark&packageManager=composer+require&packageName=spkm%2Fciphr&pattern=architect&style=style_2&description=&md=1&showWatermark=1&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

[![Tests](https://github.com/spkm/ciphr/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/spkm/ciphr/actions/workflows/tests.yml?branch=main)
[![Latest version](https://img.shields.io/packagist/v/spkm/ciphr)](https://packagist.org/packages/spkm/ciphr)
[![PHP version](https://img.shields.io/packagist/php-v/spkm/ciphr)](https://packagist.org/packages/spkm/ciphr)

A Laravel package for the [Ciphr](https://www.ciphr.com) HR API, built with [Saloon](https://docs.saloon.dev). It has a
typed model, a request and an Eloquent-style query builder for every entity Ciphr exposes: `absences()`,
`absenceCodes()`, `absenceDetails()`, `absenceReasons()`, `auditData()`, `auditLogs()`, `authenticationLogs()`,
`jobDetails()`, `leavingReasons()`, `locations()`, `organisationDetails()`, `orgChart()`, `personDetails()` (with
`jobPay()` and `qualifications()`), `personPhotos()`, `qualifications()`, `qualificationCategories()`,
`qualificationLevels()`, `qualificationSubjects()`, `titles()`, `training()`, `workPatterns()` and
`workPatternShifts()`.

```php
use spkm\Ciphr\Facades\Ciphr;

$leavers = Ciphr::personDetails()
    ->where('DateLeft', '>=', now()->subMonth())
    ->whereNotNull('WorkEmailAddress')
    ->orderByDesc('DateLeft')
    ->get();

foreach (Ciphr::absences()->where('Start', '>=', '2025-09-01')->lazy() as $absence) {
    // one page of 100 at a time
}
```

## Requirements

- PHP 8.5
- Laravel 12 or 13
- A Ciphr API key, created under System > Security > API Configuration in your portal, with read (or write)
  access to the entities and properties you need, and this server's address in its IP allow list. Properties the
  key can't read come back as `null`; an entity it can't read at all is refused.

## Installation

```bash
composer require spkm/ciphr
php artisan ciphr:install
```

The install command publishes `config/ciphr.php`. Set the portal (the subdomain of `{portal}.app.ciphr.com`) and the
key in `.env`:

```dotenv
CIPHR_PORTAL=yourportal
CIPHR_API_KEY=...
```

## Usage

### Two ways to call Ciphr

**Resources** give an Eloquent-style builder for each entity. They return models and throw a
`CiphrApiException` when Ciphr answers with an error:

```php
$person = Ciphr::personDetails()->find(2465);           // PersonDetail or null
$count = Ciphr::personDetails()->where('Status', 'Current')->count();
$titles = Ciphr::titles()->get();                        // Collection of Title
```

**Requests** are plain Saloon, for when you want the response itself:

```php
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\People\GetPersonDetailsRequest;

$response = Ciphr::send(new GetPersonDetailsRequest(Query::make()->where('Surname', 'sw', 'Sm')->orderBy('Surname')));
$response->status();
$response->json();
$response->dto();   // the same models the resource gives; throws CiphrApiException for an error status
```

`Ciphr::get()`, `post()`, `put()` and `delete()` send any path, and `Ciphr::from('/SomeEntity')` gives a builder over
an end node the package has no resource for, returning arrays. Every resource and request is listed in
[ENDPOINTS.md](ENDPOINTS.md).

### The query builder

Ciphr filters, sorts and pages in the query string, and the builder writes that string for you.

```php
Ciphr::personDetails()
    ->where('Surname', 'Smith')                      // Surname=[eq]Smith
    ->where('HolEntitlement', '>', 25)               // HolEntitlement=[gt]25
    ->whereIn('CurrentLocation', ['Surrey', 'London'])
    ->whereBetween('StartDate', ['2020-01-01', '2020-12-31'])
    ->whereNull('DateLeft')
    ->whereStartsWith('Forenames', 'Sam', caseSensitive: true)   // Forenames=[sw]"Sam"
    ->orderBy('Surname')->orderByDesc('StartDate')   // s=+Surname,-StartDate
    ->select('ID', 'Name', 'JobTitle')               // f=ID,Name,JobTitle
    ->get();
```

| Builder | Ciphr |
|---|---|
| `where($p, $value)`, `where($p, '=', $value)` | `[eq]` |
| `whereNot`, `where($p, '!=', $value)` | `[ne]` |
| `where($p, '>' / '>=' / '<' / '<=', $value)` | `[gt]` `[ge]` `[lt]` `[le]` |
| `whereIn`, `whereNotIn` | `[in]a,b` `[nin]a,b` |
| `whereBetween($p, [$from, $to])` | `[range]from[to]to` |
| `whereStartsWith`, `whereEndsWith` | `[sw]` `[ew]` |
| `whereContains`, `whereNotContains` | `[c]` `[nc]` |
| `whereNull`, `whereNotNull` | `[null]` `[notnull]` |
| `search($term)` | `q=term` |
| `changesFrom($from, $to)` | `GetChangesFrom=from[to]to` (no dates: since the start of today) |

Ciphr matches text case-insensitively; pass `caseSensitive: true` to quote the value. Dates can be strings or
`DateTimeInterface` objects. Ciphr's own grammar sets the limits: conditions on different properties are ANDed,
and several conditions on one property are ORed, so `where()` refuses a second condition on the same property and
`orWhere()` adds one:

```php
// DateLeft=[range]2000-01-01[to]2000-12-31,[ge]2010-01-01  (left in 2000, or since 2010)
Ciphr::personDetails()
    ->whereBetween('DateLeft', ['2000-01-01', '2000-12-31'])
    ->orWhere('DateLeft', '>=', '2010-01-01')
    ->get();
```

Fetching:

| Call | Returns |
|---|---|
| `get()` | a `Collection` of models, in one request (every match, or the `limit()` / `forPage()` asked for) |
| `first()`, `last()` | one model or null, in sort order (by `ID` when no sort is given) |
| `count()` | how many records match |
| `find($id)`, `findOrFail($id)` | one model by id, null (or `NotFoundException`) when there is none |
| `lazy($perPage)`, `cursor()` | a `LazyCollection` that fetches each page as the loop reaches it |
| `paginate($perPage)` | a Saloon paginator, for stepping through pages yourself |
| `toRequest()`, `toArray()` | the request, or the query parameters, the builder would send |

Ciphr pages only sorted results, so `limit()`, `forPage()`, `first()`, `last()` and `lazy()` sort by the entity's
id when you give no sort.

### Nested entities

```php
Ciphr::personDetails()->jobPay(16)->get();                // the person's job and pay history
Ciphr::personDetails()->jobPay(10)->find(1056);
Ciphr::personDetails()->qualifications(16)->get();
Ciphr::absences()->withDetail(7);                         // an Absence with its Details
Ciphr::absences()->details()->forAbsence(7);              // just the AbsenceDetail rows
Ciphr::orgChart()->get();                                 // every org chart, each with ->table() decoded
```

### Models

Every record is a `readonly` class under `spkm\Ciphr\Data` whose properties carry Ciphr's own names and types:
`Int32` as `int`, `Decimal` as `float`, `Boolean` as `bool`, `DateTime` as `CarbonImmutable` and `String` as
`string`, all nullable. Ciphr's empty date (`0001-01-01`) is `null`. Properties the key can't read, and anything
Ciphr adds later, are ignored on the way in; `toArray()` and `json_encode()` give the record back in Ciphr's keys,
leaving out what is null.

```php
$person = Ciphr::personDetails()->find(2465);
$person->Surname;
$person->StartDate?->diffInYears();
$person->ClientFieldText3;    // Ciphr's numbered custom fields are properties too
```

A portal's own further-information fields (their names begin `CF`, and some aren't valid PHP names) are not on
the models. Read them as arrays instead, with `Ciphr::from('/PersonDetail')->select('ID', 'CFSomething')->get()`
or `Ciphr::send($request)->json()`.

### Writing

Where Ciphr documents it, a resource can create, update and delete:

```php
$absence = Ciphr::absences()->create(['PerID' => 16, 'AbsCodeID' => 2, 'Start' => '2025-10-01', 'End' => '2025-10-02']);
Ciphr::absences()->update($absence->ID, ['Notes' => 'Half day']);   // the body carries the ID
Ciphr::absences()->delete($absence->ID);
Ciphr::personDetails()->update(180, new PersonDetail(WorkEmailAddress: 'someone@example.com'));
Ciphr::locations()->create([...]);  Ciphr::training()->create([...]);  Ciphr::personDetails()->jobPay(16)->create([...]);
```

`create()` and `update()` return the record Ciphr sends back, or null when it sends nothing (a 204). A resource
without a documented write throws a `LogicException` rather than guessing at a path.

### Errors

A request that Ciphr answers with an error throws a `spkm\Ciphr\CiphrApiException` (a Saloon `RequestException`)
from `dto()` and from every resource method; `Ciphr::send()` gives you the response to inspect instead.

| Ciphr's answer | Exception |
|---|---|
| 400 | `InvalidRequestException` |
| 400 saying "Unauthorized access" (the key isn't allowed the entity), 401, 403 | `UnauthorizedException` |
| 404, or a 500 with no errors to a GET by id (how Ciphr says the id doesn't exist) | `NotFoundException` |
| 410 | `GoneException` |
| 408, other 5xx | `ServerErrorException` |

The message says what happened (`Ciphr responded 400 to GET /api/PersonDetail: Malformed query string;
QuerystringError: Unrecognised property 'nosuchprop'`): Ciphr's `Message`, then the `Name` and `Description` of each
error it listed; `errors()` gives the list as an array and `ciphrMessage()` the text. Ciphr answers a payload that
fails validation with a 500 and that list, so check `ServerErrorException::errors()` before treating one as an
outage. An entity whose properties the key can't read answers "Unable to retrieve the data" as a 400.

### Several portals

Add other portals under `connections` in `config/ciphr.php` and reach them with `Ciphr::connection('name')`,
which gives a connector with the same resources and methods as the facade.

### Caching, timeouts and retries

- `CIPHR_CACHE=true` caches GET responses for `cache.seconds` (an hour) in `cache.store`.
- `CIPHR_CONNECT_TIMEOUT` and `CIPHR_REQUEST_TIMEOUT` (10 and 30 seconds).
- `CIPHR_RETRIES=3` retries a GET up to three times, with backoff, on a connection failure, 408, 429 or 5xx.
  Writes are never retried.
- `pagination.per_page` (100) sets the page size `lazy()` and `paginate()` ask for.

## Testing

```bash
composer test
```

The suite fakes Ciphr with Saloon's `MockClient`; nothing reaches a real portal.

## Contributing

Run `composer install` once to enable the pre-commit hook, which runs Pint, PHPStan and the tests.

## Security

If you discover a security issue, please don't open a public issue. [Report it privately](https://github.com/spkm/ciphr/security/advisories/new) through GitHub instead. See [SECURITY.md](SECURITY.md) for details.

## Credits

- [Simon Mitchell](https://github.com/spkm)
- [Fred Bradley](https://github.com/fredbradley)

## Licence

MIT.
