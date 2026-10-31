<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\JobDetail;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every JobDetail record: GET /JobDetail.
 */
class GetJobDetailsRequest extends ListRequest
{
    public string $resultKey = 'JobDetail';

    protected ?string $dto = JobDetail::class;

    public function resolveEndpoint(): string
    {
        return '/JobDetail';
    }
}
