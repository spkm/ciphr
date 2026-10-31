<?php

namespace spkm\Ciphr\Requests\Training;

use spkm\Ciphr\Data\Training;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One Training record: GET /Training/{id}.
 */
class GetTrainingRecordRequest extends ItemRequest
{
    public string $resultKey = 'Training';

    protected ?string $dto = Training::class;

    public function resolveEndpoint(): string
    {
        return "/Training/{$this->id}";
    }
}
