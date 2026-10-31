<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Title;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Titles\GetTitlesRequest;

/**
 * Title records, from Ciphr::titles().
 *
 * @extends Builder<Title>
 */
class TitlesResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, Title::class, 'Title', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetTitlesRequest($query);
    }
}
