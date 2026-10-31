<?php

namespace spkm\Ciphr\Requests\WorkPatterns;

use spkm\Ciphr\Data\WorkPattern;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every WorkPattern record: GET /WorkPattern.
 */
class GetWorkPatternsRequest extends ListRequest
{
    public string $resultKey = 'WorkPattern';

    protected ?string $dto = WorkPattern::class;

    public function resolveEndpoint(): string
    {
        return '/WorkPattern';
    }
}
