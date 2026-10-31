<?php

namespace spkm\Ciphr\Requests\Qualifications;

use spkm\Ciphr\Data\Qualification;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One Qualification record: GET /Qualification/{id}.
 */
class GetQualificationRequest extends ItemRequest
{
    public string $resultKey = 'Qualification';

    protected ?string $dto = Qualification::class;

    public function resolveEndpoint(): string
    {
        return "/Qualification/{$this->id}";
    }
}
