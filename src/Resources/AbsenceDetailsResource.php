<?php

namespace spkm\Ciphr\Resources;

use Illuminate\Support\Collection;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AbsenceDetail;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Absences\GetAbsenceDetailsForAbsenceRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceDetailsRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AbsenceDetail records, from Ciphr::absenceDetails().
 *
 * @extends Builder<AbsenceDetail>
 */
class AbsenceDetailsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AbsenceDetail::class, 'AbsenceDetail', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAbsenceDetailsRequest($query);
    }

    /**
     * The detail rows of one absence.
     *
     * @return Collection<int, AbsenceDetail>
     */
    public function forAbsence(int $absenceId): Collection
    {
        return $this->fetch(new GetAbsenceDetailsForAbsenceRequest($absenceId));
    }
}
