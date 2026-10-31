<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Qualification;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Qualifications\GetQualificationRequest;
use spkm\Ciphr\Requests\Qualifications\GetQualificationsRequest;

/**
 * Qualification records, from Ciphr::qualifications().
 *
 * @extends Builder<Qualification>
 */
class QualificationsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, Qualification::class, 'Qualification', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetQualificationsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetQualificationRequest($id);
    }
}
