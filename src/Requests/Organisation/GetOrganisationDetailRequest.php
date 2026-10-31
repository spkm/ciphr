<?php

namespace spkm\Ciphr\Requests\Organisation;

use spkm\Ciphr\Data\OrganisationDetail;
use spkm\Ciphr\Requests\ItemRequest;

/**
 * One OrganisationDetail record: GET /OrganisationDetail/{id}.
 */
class GetOrganisationDetailRequest extends ItemRequest
{
    public string $resultKey = 'OrganisationDetail';

    protected ?string $dto = OrganisationDetail::class;

    public function resolveEndpoint(): string
    {
        return "/OrganisationDetail/{$this->id}";
    }
}
