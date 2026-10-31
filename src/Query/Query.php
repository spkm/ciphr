<?php

namespace spkm\Ciphr\Query;

use BackedEnum;
use DateTimeInterface;
use InvalidArgumentException;
use Stringable;

/**
 * A Ciphr query string, built up immutably and compiled to the parameters Ciphr reads: ?Property=[eq]value for
 * conditions, s for sorting, f for the fields to return, q for a general search, p and l for paging, the bare flags
 * first, last and count, and GetChangesFrom.
 *
 * Ciphr ORs several conditions on one property (sent comma-separated) and ANDs conditions on different properties;
 * that is all its grammar allows.
 */
final class Query implements Stringable
{
    public const FIRST = 'first';

    public const LAST = 'last';

    public const COUNT = 'count';

    /** @var array<string, list<string>> */
    private array $conditions = [];

    /** @var list<string> */
    private array $sorts = [];

    /** @var list<string> */
    private array $fields = [];

    private ?string $search = null;

    private ?int $page = null;

    private ?int $limit = null;

    private ?string $flag = null;

    private ?string $changesFrom = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * Add a condition on a property. A second condition on the same property is ORed with the first, as Ciphr does.
     * Text is matched case-insensitively unless $caseSensitive wraps it in quotes.
     */
    public function where(string $property, Operator|string $operator, mixed $value = null, bool $caseSensitive = false): self
    {
        $operator = Operator::parse($operator);
        $query = clone $this;
        $query->conditions[self::property($property)][] = self::clause($operator, $value, $caseSensitive);

        return $query;
    }

    public function hasCondition(string $property): bool
    {
        return isset($this->conditions[self::property($property)]);
    }

    public function orderBy(string $property, string $direction = 'asc'): self
    {
        $direction = strtolower($direction);

        if (! in_array($direction, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Sort direction must be "asc" or "desc", not "'.$direction.'".');
        }

        $query = clone $this;
        $query->sorts[] = ($direction === 'desc' ? '-' : '+').self::property($property);

        return $query;
    }

    public function select(string ...$fields): self
    {
        $query = clone $this;
        $query->fields = array_values(array_unique([...$this->fields, ...array_map(self::property(...), $fields)]));

        return $query;
    }

    /**
     * Ciphr's general search (the q parameter) over the properties the portal has made searchable.
     */
    public function search(string $term, bool $caseSensitive = false): self
    {
        $query = clone $this;
        $query->search = $caseSensitive ? '"'.$term.'"' : $term;

        return $query;
    }

    public function page(?int $page): self
    {
        if ($page !== null && $page < 1) {
            throw new InvalidArgumentException('Ciphr pages start at 1.');
        }

        $query = clone $this;
        $query->page = $page;

        return $query;
    }

    public function limit(?int $limit): self
    {
        if ($limit !== null && $limit < 1) {
            throw new InvalidArgumentException('The limit must be at least 1.');
        }

        $query = clone $this;
        $query->limit = $limit;

        return $query;
    }

    public function first(): self
    {
        return $this->flag(self::FIRST);
    }

    public function last(): self
    {
        return $this->flag(self::LAST);
    }

    public function count(): self
    {
        return $this->flag(self::COUNT);
    }

    /**
     * Only the records changed since a date (or since the start of today, with no date), or between two dates.
     */
    public function changesFrom(DateTimeInterface|string|null $from = null, DateTimeInterface|string|null $to = null): self
    {
        $query = clone $this;
        $query->changesFrom = $from === null ? '' : self::date($from).($to === null ? '' : '[to]'.self::date($to));

        return $query;
    }

    public function hasSort(): bool
    {
        return $this->sorts !== [];
    }

    /**
     * Whether Ciphr needs a sort for this query: it refuses paging, first and last without one.
     */
    public function needsSort(): bool
    {
        return $this->page !== null || $this->limit !== null || in_array($this->flag, [self::FIRST, self::LAST], true);
    }

    /**
     * The query sorted by the property when it needs a sort and has none.
     */
    public function withDefaultSort(string $property): self
    {
        return $this->needsSort() && ! $this->hasSort() ? $this->orderBy($property) : $this;
    }

    public function flagged(): ?string
    {
        return $this->flag;
    }

    /**
     * The query parameters Ciphr reads. Flags are sent as bare names (?count, not ?count=), which CiphrRequest sees to.
     *
     * @return array<string, string|int>
     */
    public function toArray(): array
    {
        $query = [];

        foreach ($this->conditions as $property => $clauses) {
            $query[$property] = implode(',', $clauses);
        }

        if ($this->search !== null) {
            $query['q'] = $this->search;
        }

        if ($this->sorts !== []) {
            $query['s'] = implode(',', $this->sorts);
        }

        if ($this->fields !== []) {
            $query['f'] = implode(',', $this->fields);
        }

        if ($this->page !== null) {
            $query['p'] = $this->page;
        }

        if ($this->limit !== null) {
            $query['l'] = $this->limit;
        }

        if ($this->flag !== null) {
            $query[$this->flag] = '';
        }

        if ($this->changesFrom !== null) {
            $query['GetChangesFrom'] = $this->changesFrom;
        }

        return $query;
    }

    /**
     * The query string as Ciphr's documentation writes it, with the brackets unencoded, for logs and debugging.
     */
    public function __toString(): string
    {
        $parts = [];

        foreach ($this->toArray() as $name => $value) {
            $parts[] = $value === '' ? $name : $name.'='.$value;
        }

        return implode('&', $parts);
    }

    private function flag(string $flag): self
    {
        $query = clone $this;
        $query->flag = $flag;

        return $query;
    }

    private static function clause(Operator $operator, mixed $value, bool $caseSensitive): string
    {
        $code = '['.$operator->value.']';

        if ($operator->takesNoValue()) {
            return $code;
        }

        if ($operator->takesList()) {
            $values = is_iterable($value) ? collect($value)->all() : [$value];

            if ($values === []) {
                throw new InvalidArgumentException('An in or nin condition needs at least one value.');
            }

            return $code.implode(',', array_map(fn (mixed $item) => self::format($item, $caseSensitive), $values));
        }

        if ($operator->takesRange()) {
            if (! is_array($value) || count($value) !== 2) {
                throw new InvalidArgumentException('A range condition needs two values: [from, to].');
            }

            [$from, $to] = array_values($value);

            return $code.self::format($from, $caseSensitive).'[to]'.self::format($to, $caseSensitive);
        }

        return $code.self::format($value, $caseSensitive);
    }

    private static function format(mixed $value, bool $caseSensitive): string
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        $text = match (true) {
            $value === null => throw new InvalidArgumentException('A condition needs a value; use whereNull() for an empty property.'),
            is_bool($value) => $value ? 'true' : 'false',
            is_int($value), is_float($value) => (string) $value,
            $value instanceof DateTimeInterface => self::date($value),
            is_string($value) => $value,
            $value instanceof Stringable => (string) $value,
            default => throw new InvalidArgumentException('A condition value must be a string, number, bool, date or enum.'),
        };

        if (str_contains($text, ',')) {
            throw new InvalidArgumentException('A value cannot contain a comma: Ciphr would read "'.$text.'" as several values.');
        }

        return $caseSensitive && is_string($value) ? '"'.$text.'"' : $text;
    }

    private static function date(DateTimeInterface|string $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return $value->format('H:i:s') === '00:00:00' ? $value->format('Y-m-d') : $value->format('Y-m-d\TH:i:s');
    }

    private static function property(string $property): string
    {
        if (! preg_match('/^[A-Za-z_]\w*$/', $property)) {
            throw new InvalidArgumentException('"'.$property.'" is not a Ciphr property name.');
        }

        return $property;
    }
}
