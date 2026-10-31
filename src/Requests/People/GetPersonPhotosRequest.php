<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonPhoto;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every PersonPhoto record: GET /PersonPhoto.
 */
class GetPersonPhotosRequest extends ListRequest
{
    public string $resultKey = 'PersonPhoto';

    protected ?string $dto = PersonPhoto::class;

    public function resolveEndpoint(): string
    {
        return '/PersonPhoto';
    }
}
