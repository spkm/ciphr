<?php

namespace spkm\Ciphr\Requests;

use spkm\Ciphr\Query\Query;

/**
 * A list request for an end node the package has no class for, as Ciphr::from('/NewEntity') sends.
 */
class AdhocListRequest extends ListRequest
{
    protected bool $cacheable = false;

    /**
     * @param  Query|array<string, mixed>  $query
     */
    public function __construct(protected string $path, string $resultKey = '', string $sortKey = 'ID', Query|array $query = [])
    {
        $this->resultKey = $resultKey;
        $this->sortKey = $sortKey;

        parent::__construct($query);
    }

    public function resolveEndpoint(): string
    {
        return $this->path;
    }
}
