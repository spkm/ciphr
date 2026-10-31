<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceReason;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One AbsenceReason record: GET /AbsenceReason/{id}.
 */
class GetAbsenceReasonRequest extends ItemRequest
{
    public string $resultKey = 'AbsenceReason';

    protected ?string $dto = AbsenceReason::class;

    public function resolveEndpoint(): string
    {
        return "/AbsenceReason/{$this->id}";
    }
}
