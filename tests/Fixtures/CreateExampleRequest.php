<?php

namespace spkm\Ciphr\Tests\Fixtures;

use Saloon\Enums\Method;
use spkm\Ciphr\Requests\WriteRequest;

class CreateExampleRequest extends WriteRequest
{
    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Example';

    protected ?string $dto = ExampleData::class;

    public function resolveEndpoint(): string
    {
        return '/Example';
    }
}
