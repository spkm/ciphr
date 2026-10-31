<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\LeavingReason;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every LeavingReason record: GET /LeavingReason.
 */
class GetLeavingReasonsRequest extends ListRequest
{
    public string $resultKey = 'LeavingReason';

    protected ?string $dto = LeavingReason::class;

    public function resolveEndpoint(): string
    {
        return '/LeavingReason';
    }
}
