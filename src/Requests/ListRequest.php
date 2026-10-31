<?php

namespace spkm\Ciphr\Requests;

use Saloon\Enums\Method;
use Saloon\PaginationPlugin\Contracts\Paginatable;
use spkm\Ciphr\Query\Query;

/**
 * A GET for every record of an entity, filtered and sorted by a Query. Ciphr returns all matching records unless the
 * query pages them; CiphrPaginator pages through them 100 at a time.
 */
abstract class ListRequest extends CiphrRequest implements Paginatable
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::LIST;

    protected bool $cacheable = true;

    /**
     * The property the paginator sorts by when the query has no sort: Ciphr refuses to page unsorted results.
     */
    public string $sortKey = 'ID';

    /** @var Query|array<string, mixed> */
    protected Query|array $parameters;

    /**
     * @param  Query|array<string, mixed>  $query
     */
    public function __construct(Query|array $query = [])
    {
        $this->parameters = $query;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->parameters instanceof Query ? $this->parameters->toArray() : $this->parameters;
    }
}
