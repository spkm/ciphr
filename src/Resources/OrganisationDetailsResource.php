<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\OrganisationDetail;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Organisation\GetOrganisationDetailRequest;
use spkm\Ciphr\Requests\Organisation\GetOrganisationDetailsRequest;

/**
 * OrganisationDetail records, from Ciphr::organisationDetails().
 *
 * @extends Builder<OrganisationDetail>
 */
class OrganisationDetailsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, OrganisationDetail::class, 'OrganisationDetail', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetOrganisationDetailsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetOrganisationDetailRequest($id);
    }
}
