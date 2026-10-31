<?php

namespace spkm\Ciphr\Requests\WorkPatterns;

use spkm\Ciphr\Data\WorkPattern;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One WorkPattern record: GET /WorkPattern/{id}.
 */
class GetWorkPatternRequest extends ItemRequest
{
    public string $resultKey = 'WorkPattern';

    protected ?string $dto = WorkPattern::class;

    public function resolveEndpoint(): string
    {
        return "/WorkPattern/{$this->id}";
    }
}
