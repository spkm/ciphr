<?php

namespace spkm\Ciphr\Requests\Absences;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Update a Absence record: PUT /Absence/{id}.
 */
class UpdateAbsenceRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Absence';

    protected ?string $dto = Absence::class;

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
        return "/Absence/{$this->id}";
    }
}
