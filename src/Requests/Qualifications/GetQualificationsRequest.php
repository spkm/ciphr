<?php

namespace spkm\Ciphr\Requests\Qualifications;

use spkm\Ciphr\Data\Qualification;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every Qualification record: GET /Qualification.
 */
class GetQualificationsRequest extends ListRequest
{
    public string $resultKey = 'Qualification';

    protected ?string $dto = Qualification::class;

    public function resolveEndpoint(): string
    {
        return '/Qualification';
    }
}
