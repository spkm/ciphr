<?php

namespace spkm\Ciphr\Resources;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Location;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Locations\CreateLocationRequest;
use spkm\Ciphr\Requests\Locations\GetLocationRequest;
use spkm\Ciphr\Requests\Locations\GetLocationsRequest;
use spkm\Ciphr\Requests\Locations\UpdateLocationRequest;

/**
 * Location records, from Ciphr::locations().
 *
 * @extends Builder<Location>
 */
class LocationsResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, Location::class, 'Location', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetLocationsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetLocationRequest($id);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function createRequest(array $body): Request
    {
        return new CreateLocationRequest($body);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): Request
    {
        return new UpdateLocationRequest($id, $body);
    }
}
