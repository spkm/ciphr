<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\WorkPatternShift;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\WorkPatterns\GetWorkPatternShiftRequest;
use spkm\Ciphr\Requests\WorkPatterns\GetWorkPatternShiftsRequest;

/**
 * WorkPatternShift records, from Ciphr::workPatternShifts().
 *
 * @extends Builder<WorkPatternShift>
 */
class WorkPatternShiftsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, WorkPatternShift::class, 'WorkPatternShift', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetWorkPatternShiftsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetWorkPatternShiftRequest($id);
    }
}
