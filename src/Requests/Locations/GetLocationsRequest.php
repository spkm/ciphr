<?php

namespace spkm\Ciphr\Requests\Locations;

use spkm\Ciphr\Data\Location;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every Location record: GET /Location.
 */
class GetLocationsRequest extends ListRequest
{
    public string $resultKey = 'Location';

    protected ?string $dto = Location::class;

    public function resolveEndpoint(): string
    {
        return '/Location';
    }
}
