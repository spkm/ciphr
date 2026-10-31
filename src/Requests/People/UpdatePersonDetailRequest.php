<?php

namespace spkm\Ciphr\Requests\People;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\PersonDetail;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Update a PersonDetail record: PUT /PersonDetail/{id}.
 */
class UpdatePersonDetailRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'PersonDetail';

    protected ?string $dto = PersonDetail::class;

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
        return "/PersonDetail/{$this->id}";
    }
}
