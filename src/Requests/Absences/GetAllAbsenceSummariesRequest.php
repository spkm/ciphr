<?php

namespace spkm\ciphr\Requests\Absences;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllAbsenceSummariesRequest extends Request implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = [])
    {
        $this->setPaginationSortKey('AbsenceID', $this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return '/Absence';
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
