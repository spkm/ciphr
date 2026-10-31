<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\QualificationSubject;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Qualifications\GetQualificationSubjectsRequest;

/**
 * QualificationSubject records, from Ciphr::qualificationSubjects().
 *
 * @extends Builder<QualificationSubject>
 */
class QualificationSubjectsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, QualificationSubject::class, 'QualificationSubject', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetQualificationSubjectsRequest($query);
    }
}
