<?php

namespace spkm\Ciphr\Requests\Jobs;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Update a JobPay record: PUT /PersonDetail/{perId}/JobPay/{id}.
 */
class UpdateJobPayRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'JobPay';

    protected ?string $dto = JobPay::class;

    /**
     * @param  array<string, mixed>|JsonSerializable  $body
     */
    public function __construct(protected int $perId, protected int $id, array|JsonSerializable $body)
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
        return "/PersonDetail/{$this->perId}/JobPay/{$this->id}";
    }
}
