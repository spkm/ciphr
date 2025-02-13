<?php

namespace spkm\ciphr\Requests\OrganisationDetails;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetOrgChartDetailRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = []){}

    public function resolveEndpoint(): string
    {
        return "/OrgChartDetail";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}