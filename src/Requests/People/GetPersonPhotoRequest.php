<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonPhoto;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One PersonPhoto record: GET /PersonPhoto/{id}.
 */
class GetPersonPhotoRequest extends ItemRequest
{
    public string $resultKey = 'PersonPhoto';

    protected ?string $dto = PersonPhoto::class;

    public function resolveEndpoint(): string
    {
        return "/PersonPhoto/{$this->id}";
    }
}
