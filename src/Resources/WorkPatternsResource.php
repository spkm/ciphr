<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\WorkPattern;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\WorkPatterns\GetWorkPatternRequest;
use spkm\Ciphr\Requests\WorkPatterns\GetWorkPatternsRequest;

/**
 * WorkPattern records, from Ciphr::workPatterns().
 *
 * @extends Builder<WorkPattern>
 */
class WorkPatternsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, WorkPattern::class, 'WorkPattern', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetWorkPatternsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetWorkPatternRequest($id);
    }
}
