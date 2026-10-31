<?php

namespace spkm\Ciphr\Requests\Jobs;

use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One JobPay record: GET /PersonDetail/{perId}/JobPay/{id}.
 */
class GetJobPayRequest extends ItemRequest
{
    public string $resultKey = 'JobPay';

    protected ?string $dto = JobPay::class;

    public function __construct(protected int $perId, int $id)
    {
        parent::__construct($id);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->perId}/JobPay/{$this->id}";
    }
}
