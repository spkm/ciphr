<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\LeavingReason;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One LeavingReason record: GET /LeavingReason/{id}.
 */
class GetLeavingReasonRequest extends ItemRequest
{
    public string $resultKey = 'LeavingReason';

    protected ?string $dto = LeavingReason::class;

    public function resolveEndpoint(): string
    {
        return "/LeavingReason/{$this->id}";
    }
}
