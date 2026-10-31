<?php

namespace spkm\Ciphr\Requests\Training;

use Saloon\Enums\Method;
use spkm\Ciphr\Data\Training;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Create a Training record: POST /Training.
 */
class CreateTrainingRecordRequest extends WriteRequest
{
    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Training';

    protected ?string $dto = Training::class;

    public function resolveEndpoint(): string
    {
        return '/Training';
    }
}
