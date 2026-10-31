<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceReason;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AbsenceReason record: GET /AbsenceReason.
 */
class GetAbsenceReasonsRequest extends ListRequest
{
    public string $resultKey = 'AbsenceReason';

    protected ?string $dto = AbsenceReason::class;

    public function resolveEndpoint(): string
    {
        return '/AbsenceReason';
    }
}
