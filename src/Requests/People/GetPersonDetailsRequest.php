<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonDetail;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every PersonDetail record: GET /PersonDetail.
 */
class GetPersonDetailsRequest extends ListRequest
{
    public string $resultKey = 'PersonDetail';

    protected ?string $dto = PersonDetail::class;

    public function resolveEndpoint(): string
    {
        return '/PersonDetail';
    }
}
