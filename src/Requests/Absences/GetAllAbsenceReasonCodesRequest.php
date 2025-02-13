<?php

namespace spkm\ciphr\Requests\Absences;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\ciphr\Traits\CiphrPaginationDefaults;

class GetAllAbsenceReasonCodesRequest extends Request Implements Paginatable
{
    use CiphrPaginationDefaults;

    protected Method $method = Method::GET;

    public function __construct(protected array $queryParameters = []){
        $this->setPaginationSortKey('AbsenceReasonID',$this->queryParameters);
    }

    public function resolveEndpoint(): string
    {
        return "/AbsenceReason";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}