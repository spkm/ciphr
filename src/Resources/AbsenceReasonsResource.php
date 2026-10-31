<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AbsenceReason;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Absences\GetAbsenceReasonRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceReasonsRequest;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AbsenceReason records, from Ciphr::absenceReasons().
 *
 * @extends Builder<AbsenceReason>
 */
class AbsenceReasonsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AbsenceReason::class, 'AbsenceReason', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAbsenceReasonsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetAbsenceReasonRequest($id);
    }
}
