<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AuditData;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Audit\GetAuditDataRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AuditData records, from Ciphr::auditData().
 *
 * @extends Builder<AuditData>
 */
class AuditDataResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AuditData::class, 'AuditData', 'AuditLogID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAuditDataRequest($query);
    }
}
