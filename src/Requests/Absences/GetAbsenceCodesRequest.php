<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceCode;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AbsenceCode record: GET /AbsenceCode.
 */
class GetAbsenceCodesRequest extends ListRequest
{
    public string $resultKey = 'AbsenceCode';

    protected ?string $dto = AbsenceCode::class;

    public function resolveEndpoint(): string
    {
        return '/AbsenceCode';
    }
}
