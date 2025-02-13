<?php

namespace spkm\ciphr\Requests\Absences;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetAbsenceCodeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []){}

    public function resolveEndpoint(): string
    {
        return "/AbsenceCode/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}