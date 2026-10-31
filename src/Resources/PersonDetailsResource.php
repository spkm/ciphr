<?php

namespace spkm\Ciphr\Resources;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\PersonDetail;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\People\GetPersonDetailRequest;
use spkm\Ciphr\Requests\People\GetPersonDetailsRequest;
use spkm\Ciphr\Requests\People\UpdatePersonDetailRequest;

/**
 * PersonDetail records, from Ciphr::personDetails().
 *
 * @extends Builder<PersonDetail>
 */
class PersonDetailsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, PersonDetail::class, 'PersonDetail', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetPersonDetailsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetPersonDetailRequest($id);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): Request
    {
        return new UpdatePersonDetailRequest($id, $body);
    }

    /**
     * The job and pay history of one person.
     */
    public function jobPay(int $perId): JobPayResource
    {
        return new JobPayResource($this->connector, $perId);
    }

    /**
     * The qualifications of one person.
     */
    public function qualifications(int $perId): PersonQualificationsResource
    {
        return new PersonQualificationsResource($this->connector, $perId);
    }
}
