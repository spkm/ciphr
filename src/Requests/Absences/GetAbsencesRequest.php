<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every Absence record: GET /Absence.
 */
class GetAbsencesRequest extends ListRequest
{
    public string $resultKey = 'Absence';

    protected ?string $dto = Absence::class;

    public function resolveEndpoint(): string
    {
        return '/Absence';
    }
}
