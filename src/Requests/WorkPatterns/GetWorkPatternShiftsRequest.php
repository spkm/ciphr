<?php

namespace spkm\Ciphr\Requests\WorkPatterns;

use spkm\Ciphr\Data\WorkPatternShift;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every WorkPatternShift record: GET /WorkPatternShift.
 */
class GetWorkPatternShiftsRequest extends ListRequest
{
    public string $resultKey = 'WorkPatternShift';

    protected ?string $dto = WorkPatternShift::class;

    public function resolveEndpoint(): string
    {
        return '/WorkPatternShift';
    }
}
