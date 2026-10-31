<?php

namespace spkm\Ciphr\Requests\Organisation;

use spkm\Ciphr\Data\OrganisationDetail;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every OrganisationDetail record: GET /OrganisationDetail.
 */
class GetOrganisationDetailsRequest extends ListRequest
{
    public string $resultKey = 'OrganisationDetail';

    protected ?string $dto = OrganisationDetail::class;

    public function resolveEndpoint(): string
    {
        return '/OrganisationDetail';
    }
}
