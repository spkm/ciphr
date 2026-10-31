<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\QualificationLevel;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Qualifications\GetQualificationLevelsRequest;

/**
 * QualificationLevel records, from Ciphr::qualificationLevels().
 *
 * @extends Builder<QualificationLevel>
 */
class QualificationLevelsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, QualificationLevel::class, 'QualificationLevel', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetQualificationLevelsRequest($query);
    }
}
