<?php

namespace spkm\Ciphr\Requests\Audit;

use spkm\Ciphr\Data\AuditLog;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AuditLog record: GET /AuditLog.
 */
class GetAuditLogsRequest extends ListRequest
{
    public string $resultKey = 'AuditLog';

    protected ?string $dto = AuditLog::class;

    public function resolveEndpoint(): string
    {
        return '/AuditLog';
    }
}
