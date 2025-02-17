<?php

namespace spkm\ciphr\Requests\Qualifications;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetQualificationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []) {}

    public function resolveEndpoint(): string
    {
        return "/Qualification/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
