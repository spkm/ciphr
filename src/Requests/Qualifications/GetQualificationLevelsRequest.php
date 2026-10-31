<?php

namespace spkm\Ciphr\Requests\Qualifications;

use spkm\Ciphr\Data\QualificationLevel;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every QualificationLevel record: GET /QualificationLevel.
 */
class GetQualificationLevelsRequest extends ListRequest
{
    public string $resultKey = 'QualificationLevel';

    protected ?string $dto = QualificationLevel::class;

    public function resolveEndpoint(): string
    {
        return '/QualificationLevel';
    }
}
