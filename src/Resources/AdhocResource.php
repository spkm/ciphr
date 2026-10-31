<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\AdhocListRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * A query over an end node the package has no resource for, as Ciphr::from('/NewEntity') gives; its records are
 * arrays.
 *
 * @extends Builder<Data>
 */
final class AdhocResource extends Builder
{
    public function __construct(CiphrConnector $connector, private string $path, string $resultKey, string $sortKey = 'ID')
    {
        parent::__construct($connector, null, $resultKey, $sortKey);
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new AdhocListRequest($this->path, $this->resultKey, $this->sortKey, $query);
    }
}
