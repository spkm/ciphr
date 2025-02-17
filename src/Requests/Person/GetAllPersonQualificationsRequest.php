<?php

namespace spkm\ciphr\Requests\Person;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllPersonQualificationsRequest extends Request implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = [])
    {
        $this->setPaginationSortKey('ID', $this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->id}/PersonQualification";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
