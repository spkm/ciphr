<?php

namespace spkm\Ciphr\Requests\Audit;

use spkm\Ciphr\Data\AuthenticationLog;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every AuthenticationLog record: GET /AuthenticationLog.
 */
class GetAuthenticationLogsRequest extends ListRequest
{
    public string $resultKey = 'AuthenticationLog';

    protected ?string $dto = AuthenticationLog::class;

    public function resolveEndpoint(): string
    {
        return '/AuthenticationLog';
    }
}
