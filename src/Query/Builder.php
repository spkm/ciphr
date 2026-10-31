<?php

namespace spkm\Ciphr\Query;

use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use JsonException;
use LogicException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\Ciphr\CiphrApiException;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\CiphrPaginator;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Exceptions\NotFoundException;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Support\Payload;

/**
 * An Eloquent-style query over one Ciphr entity. Each resource extends it, saying which requests fetch, create,
 * update and delete its records; the builder turns where(), orderBy() and the rest into Ciphr's query string and
 * the response into models.
 *
 * Ciphr ORs conditions on the same property and ANDs conditions on different properties; nothing else is possible,
 * so where() refuses a second condition on a property and orWhere() adds one.
 *
 * @template TData of Data
 */
abstract class Builder
{
    protected Query $query;

    /**
     * @param  class-string<TData>|null  $dataClass  The model records are returned as; null for arrays.
     * @param  string  $resultKey  The name Ciphr wraps the entity's lists in.
     * @param  string  $sortKey  The property to sort by when the query needs a sort and gives none.
     */
    public function __construct(protected CiphrConnector $connector, protected ?string $dataClass, protected string $resultKey, protected string $sortKey = 'ID')
    {
        $this->query = Query::make();
    }

    abstract protected function listRequest(Query $query): ListRequest;

    protected function itemRequest(int $id): ?ItemRequest
    {
        return null;
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function createRequest(array $body): ?Request
    {
        return null;
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): ?Request
    {
        return null;
    }

    protected function deleteRequest(int $id): ?Request
    {
        return null;
    }

    /**
     * A condition on a property: where('Surname', 'Smith'), where('DateLeft', '>=', $date), where('ContractID', null).
     * Text matches case-insensitively unless $caseSensitive.
     */
    public function where(string $property, mixed $operator = null, mixed $value = null, bool $caseSensitive = false): static
    {
        if (func_num_args() === 2 && ! $operator instanceof Operator) {
            [$operator, $value] = $operator === null ? [Operator::Null, null] : [Operator::Equal, $operator];
        }

        if ($this->query->hasCondition($property)) {
            throw new LogicException('Ciphr can only OR conditions on the same property: use orWhere() for a second condition on "'.$property.'".');
        }

        return $this->orWhere($property, $operator, $value, $caseSensitive);
    }

    /**
     * Another condition on a property, ORed with the ones before it. Conditions on different properties stay ANDed.
     */
    public function orWhere(string $property, mixed $operator = null, mixed $value = null, bool $caseSensitive = false): static
    {
        if (func_num_args() === 2 && ! $operator instanceof Operator) {
            [$operator, $value] = $operator === null ? [Operator::Null, null] : [Operator::Equal, $operator];
        }

        $this->query = $this->query->where($property, $operator, $value, $caseSensitive);

        return $this;
    }

    public function whereNot(string $property, mixed $value, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::NotEqual, $value, $caseSensitive);
    }

    /**
     * @param  iterable<mixed>  $values
     */
    public function whereIn(string $property, iterable $values, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::In, $values, $caseSensitive);
    }

    /**
     * @param  iterable<mixed>  $values
     */
    public function whereNotIn(string $property, iterable $values, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::NotIn, $values, $caseSensitive);
    }

    /**
     * @param  array{mixed, mixed}  $range
     */
    public function whereBetween(string $property, array $range): static
    {
        return $this->where($property, Operator::Range, $range);
    }

    public function whereNull(string $property): static
    {
        return $this->where($property, Operator::Null);
    }

    public function whereNotNull(string $property): static
    {
        return $this->where($property, Operator::NotNull);
    }

    public function whereContains(string $property, string $value, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::Contains, $value, $caseSensitive);
    }

    public function whereNotContains(string $property, string $value, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::NotContains, $value, $caseSensitive);
    }

    public function whereStartsWith(string $property, string $value, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::StartsWith, $value, $caseSensitive);
    }

    public function whereEndsWith(string $property, string $value, bool $caseSensitive = false): static
    {
        return $this->where($property, Operator::EndsWith, $value, $caseSensitive);
    }

    /**
     * Ciphr's general search over the properties the portal has made searchable.
     */
    public function search(string $term, bool $caseSensitive = false): static
    {
        $this->query = $this->query->search($term, $caseSensitive);

        return $this;
    }

    /**
     * Only records changed since a date (since the start of today with no date), or between two dates.
     */
    public function changesFrom(DateTimeInterface|string|null $from = null, DateTimeInterface|string|null $to = null): static
    {
        $this->query = $this->query->changesFrom($from, $to);

        return $this;
    }

    public function orderBy(string $property, string $direction = 'asc'): static
    {
        $this->query = $this->query->orderBy($property, $direction);

        return $this;
    }

    public function orderByDesc(string $property): static
    {
        return $this->orderBy($property, 'desc');
    }

    /**
     * Only these properties in each record.
     */
    public function select(string ...$fields): static
    {
        $this->query = $this->query->select(...$fields);

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->query = $this->query->limit($limit);

        return $this;
    }

    public function forPage(int $page, int $perPage): static
    {
        $this->query = $this->query->page($page)->limit($perPage);

        return $this;
    }

    /**
     * Every matching record in one request. Ciphr returns them all unless limit() or forPage() was used.
     *
     * @return Collection<int, TData>
     */
    public function get(): Collection
    {
        /** @var Collection<int, TData> */
        return Payload::items($this->send($this->query), $this->resultKey, $this->dataClass);
    }

    /**
     * The first matching record in sort order (by the entity's default sort when none is given).
     *
     * @return TData|null
     */
    public function first(): mixed
    {
        return Payload::object($this->send($this->query->first()), $this->resultKey, $this->dataClass);
    }

    /**
     * The last matching record in sort order.
     *
     * @return TData|null
     */
    public function last(): mixed
    {
        return Payload::object($this->send($this->query->last()), $this->resultKey, $this->dataClass);
    }

    /**
     * How many records match.
     */
    public function count(): int
    {
        return Payload::count($this->send($this->query->count()));
    }

    /**
     * The record with the given id, or null when Ciphr has none.
     *
     * @return TData|null
     */
    public function find(int $id): mixed
    {
        $request = $this->itemRequest($id);

        if ($request === null) {
            return (clone $this)->where('ID', $id)->first();
        }

        try {
            return $this->fetch($request);
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * @return TData
     */
    public function findOrFail(int $id): mixed
    {
        $request = $this->itemRequest($id);

        if ($request === null) {
            $record = $this->find($id);

            if ($record === null) {
                throw new NotFoundException($this->send((clone $this->query)->where('ID', Operator::Equal, $id)->first()));
            }

            return $record;
        }

        return $this->fetch($request);
    }

    /**
     * Every matching record, fetched a page at a time as the loop reaches it.
     *
     * @return LazyCollection<int, TData>
     */
    public function lazy(?int $perPage = null): LazyCollection
    {
        $paginator = $this->paginate($perPage);
        $dataClass = $this->dataClass;

        return LazyCollection::make(function () use ($paginator, $dataClass) {
            try {
                foreach ($dataClass === null ? $paginator->collect() : $paginator->models($dataClass) as $item) {
                    yield $item;
                }
            } catch (RequestException $exception) {
                throw $exception instanceof CiphrApiException ? $exception : CiphrApiException::fromResponse($exception->getResponse());
            }
        });
    }

    /**
     * @return LazyCollection<int, TData>
     */
    public function cursor(?int $perPage = null): LazyCollection
    {
        return $this->lazy($perPage);
    }

    /**
     * A paginator over the matching records, for fetching pages one at a time yourself.
     */
    public function paginate(?int $perPage = null): CiphrPaginator
    {
        return $this->connector->paginate($this->toRequest(), $perPage);
    }

    /**
     * Create a record. Ciphr answers with the record it stored, or nothing.
     *
     * @param  array<string, mixed>|Data  $attributes
     * @return TData|null
     */
    public function create(array|Data $attributes): mixed
    {
        return $this->fetch($this->createRequest($this->attributes($attributes)) ?? $this->undocumented('create'));
    }

    /**
     * Update a record. Ciphr answers with the record as updated, or nothing.
     *
     * @param  array<string, mixed>|Data  $attributes
     * @return TData|null
     */
    public function update(int $id, array|Data $attributes): mixed
    {
        return $this->fetch($this->updateRequest($id, $this->attributes($attributes)) ?? $this->undocumented('update'));
    }

    public function delete(int $id): bool
    {
        $this->fetch($this->deleteRequest($id) ?? $this->undocumented('delete'));

        return true;
    }

    /**
     * The request get() would send, for sending through the connector yourself.
     */
    public function toRequest(): ListRequest
    {
        return $this->listRequest($this->query->withDefaultSort($this->sortKey));
    }

    public function toQuery(): Query
    {
        return $this->query->withDefaultSort($this->sortKey);
    }

    /**
     * The query parameters get() would send.
     *
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        return $this->toQuery()->toArray();
    }

    protected function send(Query $query): Response
    {
        $response = $this->connector->send($this->listRequest($query->withDefaultSort($this->sortKey)));

        if (! $response->successful()) {
            throw CiphrApiException::fromResponse($response);
        }

        return $response;
    }

    /**
     * Send a request and return what Ciphr answered as the request's model, throwing for an error status.
     */
    protected function fetch(Request $request): mixed
    {
        $response = $this->connector->send($request);

        if (! $response->successful()) {
            throw CiphrApiException::fromResponse($response);
        }

        try {
            return $response->dto();
        } catch (JsonException) {
            throw new ServerErrorException($response);
        }
    }

    /**
     * @param  array<string, mixed>|Data  $attributes
     * @return array<string, mixed>
     */
    private function attributes(array|Data $attributes): array
    {
        return $attributes instanceof Data ? $attributes->toArray() : $attributes;
    }

    private function undocumented(string $action): never
    {
        throw new LogicException("Ciphr's API documents no way to $action ".$this->resultKey.' records.');
    }
}
