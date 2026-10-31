<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\LeavingReason;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\Jobs\GetLeavingReasonRequest;
use spkm\Ciphr\Requests\Jobs\GetLeavingReasonsRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * LeavingReason records, from Ciphr::leavingReasons().
 *
 * @extends Builder<LeavingReason>
 */
class LeavingReasonsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, LeavingReason::class, 'LeavingReason', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetLeavingReasonsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetLeavingReasonRequest($id);
    }
}
