<?php

namespace spkm\ciphr\Requests\OrganisationDetails;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetOrganisationDetailRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []) {}

    public function resolveEndpoint(): string
    {
        return "/OrganisationDetail/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
