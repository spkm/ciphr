<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AbsenceCode;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Absences\GetAbsenceCodeRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceCodesRequest;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AbsenceCode records, from Ciphr::absenceCodes().
 *
 * @extends Builder<AbsenceCode>
 */
class AbsenceCodesResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AbsenceCode::class, 'AbsenceCode', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAbsenceCodesRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetAbsenceCodeRequest($id);
    }
}
