<?php

namespace spkm\Ciphr\Resources;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\Jobs\CreateJobPayRequest;
use spkm\Ciphr\Requests\Jobs\GetJobPayRequest;
use spkm\Ciphr\Requests\Jobs\GetJobPaysRequest;
use spkm\Ciphr\Requests\Jobs\UpdateJobPayRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * The job and pay records of one person.
 *
 * @extends Builder<JobPay>
 */
class JobPayResource extends Builder
{
    public function __construct(CiphrConnector $connector, private int $perId)
    {
        parent::__construct($connector, JobPay::class, 'JobPay', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetJobPaysRequest($this->perId, $query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetJobPayRequest($this->perId, $id);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function createRequest(array $body): Request
    {
        return new CreateJobPayRequest($this->perId, $body);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): Request
    {
        return new UpdateJobPayRequest($this->perId, $id, $body);
    }
}
