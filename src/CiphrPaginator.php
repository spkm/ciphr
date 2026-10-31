<?php

namespace spkm\Ciphr;

use Illuminate\Support\LazyCollection;
use JsonException;
use LogicException;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\PagedPaginator;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Requests\CiphrRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Support\Payload;

/**
 * Pages through a list with Ciphr's p (page), l (limit) and s (sort) parameters, stopping at the first empty page:
 * Ciphr doesn't say how many pages there are.
 */
class CiphrPaginator extends PagedPaginator
{
    protected ?int $perPageLimit = 100;

    public function __construct(Connector $connector, Request $request, ?int $perPage = null)
    {
        parent::__construct($connector, $request);

        if ($perPage !== null) {
            $this->perPageLimit = $perPage;
        }
    }

    /**
     * Every item on every page, as the request's models.
     *
     * @return LazyCollection<int, Data>
     */
    public function dtos(): LazyCollection
    {
        $request = $this->getOriginalRequest();
        $dto = $request instanceof CiphrRequest ? $request->dtoClass() : null;

        if ($dto === null) {
            throw new LogicException(class_basename($request).' has no model; use collect() for its items as arrays.');
        }

        return $this->models($dto);
    }

    /**
     * Every item on every page as the given model.
     *
     * @template TModel of Data
     *
     * @param  class-string<TModel>  $model
     * @return LazyCollection<int, TModel>
     */
    public function models(string $model): LazyCollection
    {
        return $this->collect()->map(fn (array $item) => $model::from($item));
    }

    protected function applyPagination(Request $request): Request
    {
        $request->query()->add('p', $this->currentPage + 1);

        if (isset($this->perPageLimit)) {
            $request->query()->add('l', $this->perPageLimit);
        }

        // Ciphr refuses to page unsorted results.
        if (! $request->query()->get('s')) {
            $request->query()->add('s', '+'.($request instanceof ListRequest ? $request->sortKey : 'ID'));
        }

        return $request;
    }

    protected function isLastPage(Response $response): bool
    {
        return $this->getPageItems($response, $response->getRequest()) === [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function getPageItems(Response $response, Request $request): array
    {
        try {
            return Payload::records($response, $request instanceof CiphrRequest ? $request->resultKey : '');
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }
    }

    protected function getTotalPages(Response $response): int
    {
        throw new LogicException('Ciphr does not say how many pages a list has, so its pages cannot be fetched asynchronously.');
    }
}
