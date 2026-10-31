<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\JobDetail;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\Jobs\GetJobDetailRequest;
use spkm\Ciphr\Requests\Jobs\GetJobDetailsRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * JobDetail records, from Ciphr::jobDetails().
 *
 * @extends Builder<JobDetail>
 */
class JobDetailsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, JobDetail::class, 'JobDetail', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetJobDetailsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetJobDetailRequest($id);
    }
}
