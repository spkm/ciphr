<?php

namespace spkm\Ciphr\Requests\WorkPatterns;

use spkm\Ciphr\Data\WorkPatternShift;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One WorkPatternShift record: GET /WorkPatternShift/{id}.
 */
class GetWorkPatternShiftRequest extends ItemRequest
{
    public string $resultKey = 'WorkPatternShift';

    protected ?string $dto = WorkPatternShift::class;

    public function resolveEndpoint(): string
    {
        return "/WorkPatternShift/{$this->id}";
    }
}
