<?php

namespace spkm\Ciphr\Requests\Absences;

use Saloon\Enums\Method;
use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Requests\WriteRequest;

/**
 * Create a Absence record: POST /Absence.
 */
class CreateAbsenceRequest extends WriteRequest
{
    protected Method $method = Method::POST;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Absence';

    protected ?string $dto = Absence::class;

    public function resolveEndpoint(): string
    {
        return '/Absence';
    }
}
