<?php

namespace spkm\Ciphr\Requests\Audit;

use spkm\Ciphr\Data\AuditData;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AuditData record: GET /AuditData.
 */
class GetAuditDataRequest extends ListRequest
{
    public string $resultKey = 'AuditData';

    protected ?string $dto = AuditData::class;

    public string $sortKey = 'AuditLogID';

    public function resolveEndpoint(): string
    {
        return '/AuditData';
    }
}
