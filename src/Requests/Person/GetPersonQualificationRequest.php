<?php

namespace spkm\ciphr\Requests\Person;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPersonQualificationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $personId,protected int $qualificationId, protected array $queryParameters = []){}

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->personId}/PersonQualification/{$this->qualificationId}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}