<?php

namespace spkm\Ciphr\Tests\Fixtures;

use spkm\Ciphr\Requests\ListRequest;

class GetExamplesRequest extends ListRequest
{
    public string $resultKey = 'Example';

    protected ?string $dto = ExampleData::class;

    public function resolveEndpoint(): string
    {
        return '/Example';
    }
}
