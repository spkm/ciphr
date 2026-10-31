<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One Absence record: GET /Absence/{id}.
 */
class GetAbsenceRequest extends ItemRequest
{
    public string $resultKey = 'Absence';

    protected ?string $dto = Absence::class;

    public function resolveEndpoint(): string
    {
        return "/Absence/{$this->id}";
    }
}
