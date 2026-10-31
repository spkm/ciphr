<?php

namespace spkm\Ciphr\Data;

use BackedEnum;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use JsonSerializable;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

abstract readonly class Data implements JsonSerializable
{
    /**
     * The model class of each list property, keyed by property name.
     *
     * @var array<string, class-string<Data>>
     */
    protected const LISTS = [];

    /**
     * @param  array<string, mixed>  $attributes
     */
    public static function from(array $attributes): static
    {
        $arguments = [];

        foreach (self::parameters(static::class) as $parameter) {
            $value = static::cast($parameter, $attributes[$parameter->getName()] ?? null);

            if ($value === null && ! $parameter->isDefaultValueAvailable() && ! $parameter->allowsNull()) {
                throw new InvalidArgumentException(class_basename(static::class).' needs a value for "'.$parameter->getName().'".');
            }

            if ($value !== null || ! $parameter->isDefaultValueAvailable()) {
                $arguments[$parameter->getName()] = $value;
            }
        }

        return self::reflect(static::class)->newInstanceArgs($arguments);
    }

    /**
     * @param  iterable<array<string, mixed>>|null  $items
     * @return Collection<int, static>
     */
    public static function collect(?iterable $items): Collection
    {
        return collect($items ?? [])->map(fn (array $item) => static::from($item))->values();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $emptyByDefault = self::emptyByDefault(static::class);

        return collect(get_object_vars($this))
            ->map(fn (mixed $value) => static::serialise($value))
            ->reject(fn (mixed $value, string $name) => $value === null || ($value === [] && isset($emptyByDefault[$name])))
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * A model's constructor parameters, read once per class: reflecting them for every record is slow over a whole
     * school. (A readonly class can't have static properties, so the cache is a static variable.)
     *
     * @param  class-string<self>  $class
     * @return list<ReflectionParameter>
     */
    private static function parameters(string $class): array
    {
        static $parameters = [];

        return $parameters[$class] ??= self::reflect($class)->getConstructor()?->getParameters() ?? [];
    }

    /**
     * @template TModel of self
     *
     * @param  class-string<TModel>  $class
     * @return ReflectionClass<TModel>
     */
    private static function reflect(string $class): ReflectionClass
    {
        static $classes = [];

        return $classes[$class] ??= new ReflectionClass($class);
    }

    /**
     * The properties whose default serialises to an empty list (an empty collection or array), which toArray() leaves
     * out while they are still empty, keyed by name.
     *
     * @param  class-string<self>  $class
     * @return array<string, true>
     */
    private static function emptyByDefault(string $class): array
    {
        static $names = [];

        if (! isset($names[$class])) {
            $names[$class] = [];

            foreach (self::parameters($class) as $parameter) {
                if ($parameter->isDefaultValueAvailable() && static::serialise($parameter->getDefaultValue()) === []) {
                    $names[$class][$parameter->getName()] = true;
                }
            }
        }

        return $names[$class];
    }

    protected static function cast(ReflectionParameter $parameter, mixed $value): mixed
    {
        $type = $parameter->getType();
        $typeName = $type instanceof ReflectionNamedType ? $type->getName() : 'mixed';

        if ($typeName === Collection::class) {
            $class = static::LISTS[$parameter->getName()] ?? null;

            return is_iterable($value) ? collect($value)->map(fn (mixed $item) => $class && is_array($item) ? $class::from($item) : $item)->values() : null;
        }

        if ($value === null) {
            return null;
        }

        return match (true) {
            $typeName === 'int' => is_numeric($value) ? (int) $value : null,
            $typeName === 'float' => is_numeric($value) ? (float) $value : null,
            $typeName === 'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            $typeName === 'string' => is_scalar($value) ? (string) $value : null,
            $typeName === 'array' => is_array($value) ? $value : null,
            $typeName === CarbonImmutable::class => static::date($value),
            is_subclass_of($typeName, self::class) => is_array($value) ? $typeName::from($value) : null,
            default => $value,
        };
    }

    protected static function date(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || str_starts_with($value, '0001-01-01')) {
            return null;
        }

        try {
            return preg_match('#^\d{2}/\d{2}/\d{4}$#', $value) ? CarbonImmutable::createFromFormat('!d/m/Y', $value) ?: null : CarbonImmutable::parse($value);
        } catch (InvalidFormatException) {
            return null;
        }
    }

    protected static function serialise(mixed $value): mixed
    {
        return match (true) {
            $value instanceof self => $value->toArray(),
            $value instanceof Collection => $value->map(fn (mixed $item) => static::serialise($item))->values()->all(),
            $value instanceof DateTimeInterface => $value->format('Y-m-d\TH:i:s'),
            $value instanceof BackedEnum => $value->value,
            default => $value,
        };
    }
}
