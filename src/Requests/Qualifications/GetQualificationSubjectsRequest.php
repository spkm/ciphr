<?php

namespace spkm\Ciphr\Requests\Qualifications;

use spkm\Ciphr\Data\QualificationSubject;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every QualificationSubject record: GET /QualificationSubject.
 */
class GetQualificationSubjectsRequest extends ListRequest
{
    public string $resultKey = 'QualificationSubject';

    protected ?string $dto = QualificationSubject::class;

    public function resolveEndpoint(): string
    {
        return '/QualificationSubject';
    }
}
