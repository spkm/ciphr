<?php

namespace spkm\Ciphr\Requests\Locations;

use spkm\Ciphr\Data\Location;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One Location record: GET /Location/{id}.
 */
class GetLocationRequest extends ItemRequest
{
    public string $resultKey = 'Location';

    protected ?string $dto = Location::class;

    public function resolveEndpoint(): string
    {
        return "/Location/{$this->id}";
    }
}
