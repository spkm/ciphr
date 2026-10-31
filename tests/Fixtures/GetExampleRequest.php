<?php

namespace spkm\Ciphr\Tests\Fixtures;

use spkm\Ciphr\Requests\ItemRequest;

class GetExampleRequest extends ItemRequest
{
    public string $resultKey = 'Example';

    protected ?string $dto = ExampleData::class;

    public function resolveEndpoint(): string
    {
        return "/Example/{$this->id}";
    }
}
