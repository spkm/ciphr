<?php

namespace spkm\Ciphr\Tests\Fixtures;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * @extends Builder<ExampleData>
 */
class ExampleResource extends Builder
{
    public function __construct(CiphrConnector $connector, private bool $writable = true, private bool $hasItemRequest = true)
    {
        parent::__construct($connector, ExampleData::class, 'Example');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetExamplesRequest($query);
    }

    protected function itemRequest(int $id): ?ItemRequest
    {
        return $this->hasItemRequest ? new GetExampleRequest($id) : null;
    }

    protected function createRequest(array $body): ?Request
    {
        return $this->writable ? new CreateExampleRequest($body) : null;
    }

    protected function updateRequest(int $id, array $body): ?Request
    {
        return $this->writable ? new UpdateExampleRequest($id, $body) : null;
    }

    protected function deleteRequest(int $id): ?Request
    {
        return $this->writable ? new DeleteExampleRequest($id) : null;
    }
}
