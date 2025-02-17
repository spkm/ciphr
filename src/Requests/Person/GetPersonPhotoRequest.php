<?php

namespace spkm\ciphr\Requests\Person;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPersonPhotoRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []) {}

    public function resolveEndpoint(): string
    {
        return "/PersonPhoto/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
