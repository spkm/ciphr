<?php

namespace spkm\Ciphr\Requests\Training;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\Training;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Update a Training record: PUT /Training/{id}.
 */
class UpdateTrainingRecordRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Training';

    protected ?string $dto = Training::class;

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
        return "/Training/{$this->id}";
    }
}
