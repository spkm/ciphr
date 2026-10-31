<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AuditLog;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Audit\GetAuditLogsRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AuditLog records, from Ciphr::auditLogs().
 *
 * @extends Builder<AuditLog>
 */
class AuditLogsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AuditLog::class, 'AuditLog', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAuditLogsRequest($query);
    }
}
