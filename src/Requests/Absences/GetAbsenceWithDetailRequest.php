<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One Absence record with its detail rows: GET /Absence/{id}/WithDetail.
 */
class GetAbsenceWithDetailRequest extends ItemRequest
{
    public string $resultKey = 'Absence';

    protected ?string $dto = Absence::class;

    public function resolveEndpoint(): string
    {
        return "/Absence/{$this->id}/WithDetail";
    }
}
