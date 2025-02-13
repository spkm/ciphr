<?php

namespace spkm\ciphr\Requests\Qualifications;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllQualificationsRequest extends Request Implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = []){
        $this->setPaginationSortKey('ID',$this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return "/Qualification";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}