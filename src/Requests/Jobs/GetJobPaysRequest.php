<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;

/**
 * The JobPay records under the path: GET /PersonDetail/{perId}/JobPay.
 */
class GetJobPaysRequest extends ListRequest
{
    public string $resultKey = 'JobPay';

    protected ?string $dto = JobPay::class;

    /**
     * @param  Query|array<string, mixed>  $query
     */
    public function __construct(protected int $perId, Query|array $query = [])
    {
        parent::__construct($query);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->perId}/JobPay";
    }
}
