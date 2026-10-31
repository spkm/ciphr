<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\JobDetail;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One JobDetail record: GET /JobDetail/{id}.
 */
class GetJobDetailRequest extends ItemRequest
{
    public string $resultKey = 'JobDetail';

    protected ?string $dto = JobDetail::class;

    public function resolveEndpoint(): string
    {
        return "/JobDetail/{$this->id}";
    }
}
