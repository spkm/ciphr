<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceDetail;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AbsenceDetail record: GET /AbsenceDetail.
 */
class GetAbsenceDetailsRequest extends ListRequest
{
    public string $resultKey = 'AbsenceDetail';

    protected ?string $dto = AbsenceDetail::class;

    public function resolveEndpoint(): string
    {
        return '/AbsenceDetail';
    }
}
