<?php

namespace spkm\ciphr\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPersonDetailRequest extends Request
{
    protected Method $method = Method::GET;
    public int $personId;

    public function __construct(int $personId, protected array $queryParameters = [])
    {
        $this->personId = $personId;
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->personId}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}