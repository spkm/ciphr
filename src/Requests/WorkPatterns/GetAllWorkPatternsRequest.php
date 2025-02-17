<?php

namespace spkm\ciphr\Requests\WorkPatterns;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllWorkPatternsRequest extends Request implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = [])
    {
        $this->setPaginationSortKey('WorkPatternID', $this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return '/WorkPattern';
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
