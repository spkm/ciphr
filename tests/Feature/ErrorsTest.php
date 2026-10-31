<?php

use Saloon\Http\Faking\MockResponse;
use spkm\Ciphr\CiphrApiException;
use spkm\Ciphr\Exceptions\GoneException;
use spkm\Ciphr\Exceptions\InvalidRequestException;
use spkm\Ciphr\Exceptions\NotFoundException;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Exceptions\UnauthorizedException;
use spkm\Ciphr\Tests\Fixtures\CreateExampleRequest;
use spkm\Ciphr\Tests\Fixtures\GetExampleRequest;
use spkm\Ciphr\Tests\Fixtures\GetExamplesRequest;
use spkm\Ciphr\Tests\Helpers\FakeCiphr;

it('throws the exception for the status', function (int $status, string $class) {
    $response = FakeCiphr::send(new GetExampleRequest(1), ['Error' => [['Name' => 'Oops', 'Description' => 'Something']]], $status);

    expect(fn () => $response->dto())->toThrow($class, "Ciphr responded $status to GET /api/Example/1: Oops: Something");
})->with([
    [404, NotFoundException::class],
    [410, GoneException::class],
    [400, InvalidRequestException::class],
    [401, UnauthorizedException::class],
    [403, UnauthorizedException::class],
    [408, ServerErrorException::class],
    [500, ServerErrorException::class],
    [503, ServerErrorException::class],
    [418, CiphrApiException::class],
]);

it('lists the errors Ciphr sent', function () {
    $response = FakeCiphr::send(new CreateExampleRequest(['Name' => '']), ['Error' => [['Name' => 'Name', 'Description' => 'is required'], ['Description' => 'and something else'], 'junk']], 500);

    try {
        $response->dto();
        $this->fail('No exception');
    } catch (ServerErrorException $exception) {
        expect($exception->errors())->toBe([['Name' => 'Name', 'Description' => 'is required'], ['Name' => '', 'Description' => 'and something else']])
            ->and($exception->ciphrMessage())->toBe('Name: is required; and something else')
            ->and($exception->getMessage())->toBe('Ciphr responded 500 to POST /api/Example: Name: is required; and something else');
    }
});

it('reads Ciphr\'s mislabelled answers for what they mean', function () {
    // A key without access to an entity: a 400 saying so.
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, ['Message' => 'Unauthorized access to API method.', 'Error' => []], 400)->dto())
        ->toThrow(UnauthorizedException::class, 'Ciphr responded 400 to GET /api/Example: Unauthorized access to API method');
    // The gateway's plain-text refusal of a key.
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, "Message: An unauthorized request has been made\n", 400)->dto())
        ->toThrow(UnauthorizedException::class, 'Ciphr responded 400 to GET /api/Example: An unauthorized request has been made');
    // A GET by id for a record Ciphr doesn't have: a 500 with no errors.
    expect(fn () => FakeCiphr::send(new GetExampleRequest(999), ['Message' => 'We have experienced a problem, please retry and if the problem persists please contact our support team.', 'Error' => []], 500)->dto())
        ->toThrow(NotFoundException::class, 'Ciphr responded 500 to GET /api/Example/999: We have experienced a problem');
    // The same answer to a list is a real fault.
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, ['Message' => 'We have experienced a problem.', 'Error' => []], 500)->dto())
        ->toThrow(ServerErrorException::class);
    // A malformed query: Message first, then each error.
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, ['Message' => 'Malformed query string.', 'Error' => [['Name' => 'QuerystringError', 'Description' => "Unrecognised property 'nosuchprop'."]]], 400)->dto())
        ->toThrow(InvalidRequestException::class, "Ciphr responded 400 to GET /api/Example: Malformed query string; QuerystringError: Unrecognised property 'nosuchprop'");
});

it('describes an error without Ciphr\'s error body', function () {
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, 'Internal error', 500)->dto())
        ->toThrow(ServerErrorException::class, 'Ciphr responded 500 to GET /api/Example: Internal error');
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, '', 500)->dto())
        ->toThrow(ServerErrorException::class, 'Ciphr responded 500 to GET /api/Example');
    expect(fn () => FakeCiphr::send(new GetExamplesRequest, '<html><head><title>502 Bad Gateway</title></head></html>', 502)->dto())
        ->toThrow(ServerErrorException::class, "with an HTML error page ('502 Bad Gateway')");

    try {
        FakeCiphr::send(new GetExamplesRequest, ['message' => 'nope'], 500)->dto();
    } catch (ServerErrorException $exception) {
        expect($exception->errors())->toBe([])->and($exception->ciphrMessage())->toBeNull();
    }

    try {
        FakeCiphr::send(new GetExamplesRequest, ['Message' => 'Unable to retrieve the data.', 'Error' => []], 400)->dto();
    } catch (InvalidRequestException $exception) {
        expect($exception->errors())->toBe([])->and($exception->ciphrMessage())->toBe('Unable to retrieve the data');
    }
});

it('retries a safe request on a fault when retries are configured, and never a write', function () {
    config()->set('ciphr.retries.times', 3);
    config()->set('ciphr.retries.interval_ms', 0);
    $mockClient = FakeCiphr::respondWith([
        GetExamplesRequest::class => MockResponse::make('', 503),
        CreateExampleRequest::class => MockResponse::make('', 503),
    ]);
    $connector = FakeCiphr::connector();

    expect($connector->send(new GetExamplesRequest)->status())->toBe(503)
        ->and(FakeCiphr::queriesReceived($mockClient, GetExamplesRequest::class))->toHaveCount(3)
        ->and($connector->send(new CreateExampleRequest(['Name' => 'x']))->status())->toBe(503)
        ->and(collect($mockClient->getRecordedResponses())->filter(fn ($r) => $r->getRequest() instanceof CreateExampleRequest))->toHaveCount(1);
});

it('does not retry a 404', function () {
    config()->set('ciphr.retries.times', 3);
    $mockClient = FakeCiphr::respondWith([GetExamplesRequest::class => MockResponse::make('', 404)]);

    FakeCiphr::connector()->send(new GetExamplesRequest);

    expect($mockClient->getRecordedResponses())->toHaveCount(1);
});
