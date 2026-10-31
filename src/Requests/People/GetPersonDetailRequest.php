<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonDetail;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One PersonDetail record: GET /PersonDetail/{id}.
 */
class GetPersonDetailRequest extends ItemRequest
{
    public string $resultKey = 'PersonDetail';

    protected ?string $dto = PersonDetail::class;

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->id}";
    }
}
