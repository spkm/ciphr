<?php

namespace spkm\Ciphr\Requests\Jobs;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Data\JobPay;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Create a JobPay record: POST /PersonDetail/{perId}/JobPay.
 */
class CreateJobPayRequest extends WriteRequest
{
    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'JobPay';

    protected ?string $dto = JobPay::class;

    /**
     * @param  array<string, mixed>|JsonSerializable  $body
     */
    public function __construct(protected int $perId, array|JsonSerializable $body)
    {
        parent::__construct($body);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->perId}/JobPay";
    }
}
