<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonQualification;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One PersonQualification record: GET /PersonDetail/{perId}/PersonQualification/{id}.
 */
class GetPersonQualificationRequest extends ItemRequest
{
    public string $resultKey = 'PersonQualification';

    protected ?string $dto = PersonQualification::class;

    public function __construct(protected int $perId, int $id)
    {
        parent::__construct($id);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->perId}/PersonQualification/{$this->id}";
    }
}
