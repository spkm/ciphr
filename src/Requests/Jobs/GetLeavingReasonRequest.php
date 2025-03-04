<?php

namespace spkm\ciphr\Requests\Jobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetLeavingReasonRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []) {}

    public function resolveEndpoint(): string
    {
        return "/LeavingReason/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
