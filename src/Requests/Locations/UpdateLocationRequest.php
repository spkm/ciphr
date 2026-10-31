<?php

namespace spkm\Ciphr\Requests\Locations;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\Location;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Update a Location record: PUT /Location/{id}.
 */
class UpdateLocationRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Location';

    protected ?string $dto = Location::class;

    /**
     * @param  array<string, mixed>|JsonSerializable  $body
     */
    public function __construct(protected int $id, array|JsonSerializable $body)
    {
        parent::__construct($body);
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return ['ID' => $this->id, ...parent::defaultBody()];
    }

    public function resolveEndpoint(): string
    {
        return "/Location/{$this->id}";
    }
}
