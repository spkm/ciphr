<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceCode;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One AbsenceCode record: GET /AbsenceCode/{id}.
 */
class GetAbsenceCodeRequest extends ItemRequest
{
    public string $resultKey = 'AbsenceCode';

    protected ?string $dto = AbsenceCode::class;

    public function resolveEndpoint(): string
    {
        return "/AbsenceCode/{$this->id}";
    }
}
