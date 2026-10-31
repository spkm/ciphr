<?php

namespace spkm\Ciphr\Requests\Locations;

use Saloon\Enums\Method;
use spkm\Ciphr\Data\Location;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Create a Location record: POST /Location.
 */
class CreateLocationRequest extends WriteRequest
{
    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Location';

    protected ?string $dto = Location::class;

    public function resolveEndpoint(): string
    {
        return '/Location';
    }
}
