<?php

namespace spkm\ciphr\Requests\Jobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllJobDetailsRequest extends Request implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = [])
    {
        $this->setPaginationSortKey('ID', $this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return '/JobDetail';
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
