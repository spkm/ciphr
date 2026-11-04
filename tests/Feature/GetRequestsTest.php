<?php

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\CiphrRequest;
use spkm\Ciphr\Requests\People\GetPersonDetailsRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

/**
 * Ciphr's firewall blocks a GET that carries a body, so filters must only ever travel in the query string.
 *
 * @return list<Response>
 */
function sentGets(MockClient $mockClient): array
{
    return collect($mockClient->getRecordedResponses())
        ->filter(fn (Response $response) => $response->getPsrRequest()->getMethod() === 'GET')
        ->values()
        ->all();
}

it('sends every resource\'s reads with no body and no Content-Type', function () {
    $mockClient = FakeCiphr::respondWith([
        '*' => fn (PendingRequest $request) => MockResponse::make($request->query()->get(Query::COUNT) !== null ? '0' : []),
    ]);
    $connector = FakeCiphr::connector();
    $resources = collect((new ReflectionClass(CiphrConnector::class))->getMethods())
        ->filter(fn (ReflectionMethod $method) => $method->getNumberOfParameters() === 0
            && $method->getReturnType() instanceof ReflectionNamedType
            && is_subclass_of($method->getReturnType()->getName(), Builder::class))
        ->map(fn (ReflectionMethod $method) => $method->getName());

    foreach ($resources as $resource) {
        $connector->$resource()->where('ID', '>', 5)->whereIn('Status', ['A', 'B'])->search('smith')->select('ID')
            ->orderByDesc('ID')->changesFrom('2026-01-01')->forPage(2, 10)->get();
        $connector->$resource()->where('ID', 1)->first();
        $connector->$resource()->whereNull('ID')->count();
        $connector->$resource()->find(1);
        $connector->$resource()->where('ID', '>', 1)->lazy(5)->all();
    }
    $connector->get('/PersonDetail', ['Surname' => '[eq]Smith']);
    $connector->from('/NewEntity')->where('Name', 'x')->get();
    $connector->paginate(new GetPersonDetailsRequest(Query::make()->where('Surname', '=', 'Smith')))->collect()->all();

    $gets = sentGets($mockClient);

    expect($resources)->not->toBeEmpty()
        ->and(count($gets))->toBeGreaterThan($resources->count() * 5);

    foreach ($gets as $response) {
        $psr = $response->getPsrRequest();

        expect($response->getPendingRequest()->body())->toBeNull()
            ->and($psr->getBody()->getSize())->toBe(0)
            ->and($psr->hasHeader('Content-Type'))->toBeFalse((string) $psr->getUri())
            ->and($psr->hasHeader('Content-Length'))->toBeFalse((string) $psr->getUri());
    }
});

it('sends filters as query parameters', function () {
    $mockClient = FakeCiphr::respondWith(['*' => MockResponse::make([])]);

    FakeCiphr::connector()->personDetails()->where('Surname', 'Smith')->whereIn('Status', ['A', 'B'])->get();
    $psr = sentGets($mockClient)[0]->getPsrRequest();

    expect(urldecode($psr->getUri()->getQuery()))->toBe('Surname=[eq]Smith&Status=[in]A,B')
        ->and((string) $psr->getBody())->toBe('');
});

it('refuses to send a GET with a body', function () {
    FakeCiphr::respondWith(['*' => MockResponse::make([])]);
    $request = new class extends CiphrRequest implements HasBody
    {
        use HasJsonBody;

        protected Method $method = Method::GET;

        public function resolveEndpoint(): string
        {
            return '/PersonDetail';
        }

        protected function defaultBody(): array
        {
            return ['Surname' => 'Smith'];
        }
    };

    FakeCiphr::connector()->send($request);
})->throws(LogicException::class, 'query parameters');

it('still sends Content-Type with a JSON body', function () {
    $mockClient = FakeCiphr::respondWith(['*' => MockResponse::make([])]);

    FakeCiphr::connector()->post('/Absence', ['PersonID' => 1]);
    $psr = $mockClient->getLastResponse()->getPsrRequest();

    expect($psr->getHeaderLine('Content-Type'))->toBe('application/json')
        ->and((string) $psr->getBody())->toBe('{"PersonID":1}');
});
