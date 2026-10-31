<?php

namespace spkm\Ciphr\Requests\Qualifications;

use spkm\Ciphr\Data\QualificationCategory;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every QualificationCategory record: GET /QualificationCategory.
 */
class GetQualificationCategoriesRequest extends ListRequest
{
    public string $resultKey = 'QualificationCategory';

    protected ?string $dto = QualificationCategory::class;

    public function resolveEndpoint(): string
    {
        return '/QualificationCategory';
    }
}
