<?php

namespace spkm\Ciphr\Requests\Training;

use spkm\Ciphr\Data\Training;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every Training record: GET /Training.
 */
class GetTrainingRecordsRequest extends ListRequest
{
    public string $resultKey = 'Training';

    protected ?string $dto = Training::class;

    public function resolveEndpoint(): string
    {
        return '/Training';
    }
}
