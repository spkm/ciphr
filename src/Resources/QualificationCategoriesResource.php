<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\QualificationCategory;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Qualifications\GetQualificationCategoriesRequest;

/**
 * QualificationCategory records, from Ciphr::qualificationCategories().
 *
 * @extends Builder<QualificationCategory>
 */
class QualificationCategoriesResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, QualificationCategory::class, 'QualificationCategory', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetQualificationCategoriesRequest($query);
    }
}
