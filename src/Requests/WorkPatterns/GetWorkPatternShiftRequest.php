<?php

namespace spkm\ciphr\Requests\WorkPatterns;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWorkPatternShiftRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected int $id, protected array $queryParameters = []) {}

    public function resolveEndpoint(): string
    {
        return "/WorkPatternShift/{$this->id}";
    }

    public function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
