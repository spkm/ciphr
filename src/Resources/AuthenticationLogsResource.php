<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\AuthenticationLog;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Audit\GetAuthenticationLogsRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * AuthenticationLog records, from Ciphr::authenticationLogs().
 *
 * @extends Builder<AuthenticationLog>
 */
class AuthenticationLogsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, AuthenticationLog::class, 'AuthenticationLog', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAuthenticationLogsRequest($query);
    }
}
