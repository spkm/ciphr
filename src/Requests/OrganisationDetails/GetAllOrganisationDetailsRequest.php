<?php

namespace spkm\ciphr\Requests\OrganisationDetails;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllOrganisationDetailsRequest extends Request implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = [])
    {
        $this->setPaginationSortKey('OrganisationDetailID', $this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return '/OrganisationDetails';
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
