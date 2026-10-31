<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\PersonQualification;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\People\GetPersonQualificationRequest;
use spkm\Ciphr\Requests\People\GetPersonQualificationsRequest;

/**
 * The qualifications of one person.
 *
 * @extends Builder<PersonQualification>
 */
class PersonQualificationsResource extends Builder
{
    public function __construct(CiphrConnector $connector, private int $perId)
    {
        parent::__construct($connector, PersonQualification::class, 'PersonQualification', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetPersonQualificationsRequest($this->perId, $query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetPersonQualificationRequest($this->perId, $id);
    }
}
