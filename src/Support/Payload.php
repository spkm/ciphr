<?php

namespace spkm\Ciphr\Support;

use Illuminate\Support\Collection;
use Saloon\Http\Response;
use spkm\Ciphr\Data\Data;
use UnexpectedValueException;

/**
 * Reads Ciphr's response bodies, whichever shape they take: a list wrapped in the entity's name
 * ({"PersonDetail": [...]}), a bare list, a bare object, or a bare number for ?count.
 */
final class Payload
{
    /**
     * The decoded JSON body, or null when the body is empty. Throws JsonException for anything that isn't JSON.
     */
    public static function json(Response $response): mixed
    {
        $body = $response->body();

        return $body === '' ? null : json_decode($body, true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * The records in the response, as arrays.
     *
     * @return list<array<string, mixed>>
     */
    public static function records(Response $response, string $key): array
    {
        $json = self::json($response);

        if ($json === null || $json === []) {
            return [];
        }

        if (! is_array($json)) {
            throw new UnexpectedValueException("Ciphr's response to ".self::requestLine($response).' is not a list of '.($key === '' ? 'records' : $key.' records').'.');
        }

        if (array_is_list($json)) {
            return self::onlyArrays($json);
        }

        $wrapped = $key !== '' && isset($json[$key]) ? $json[$key] : null;

        if ($wrapped === null && count($json) === 1 && is_array(reset($json)) && array_is_list(reset($json))) {
            $wrapped = reset($json);
        }

        if (is_array($wrapped)) {
            return array_is_list($wrapped) ? self::onlyArrays($wrapped) : [$wrapped];
        }

        return [$json];
    }

    /**
     * The records as models of the request's class, or as arrays when it has none.
     *
     * @param  class-string<Data>|null  $dto
     * @return Collection<int, Data|array<string, mixed>>
     */
    public static function items(Response $response, string $key, ?string $dto): Collection
    {
        return collect(self::records($response, $key))->map(fn (array $item) => $dto === null ? $item : $dto::from($item));
    }

    /**
     * The one record in the response, or null when Ciphr sent nothing.
     *
     * @param  class-string<Data>|null  $dto
     * @return Data|array<string, mixed>|null
     */
    public static function object(Response $response, string $key, ?string $dto): mixed
    {
        $record = self::records($response, $key)[0] ?? null;

        return $record === null || $dto === null ? $record : $dto::from($record);
    }

    /**
     * The number Ciphr answers ?count with: bare, or alone in an object such as {"Count": 12}.
     */
    public static function count(Response $response): int
    {
        $count = self::countIn(self::json($response));

        if ($count === null) {
            throw new UnexpectedValueException("Ciphr's response to ".self::requestLine($response).' is not a count.');
        }

        return $count;
    }

    private static function countIn(mixed $json): ?int
    {
        if (is_array($json) && count($json) === 1) {
            $json = reset($json);
        }

        return is_int($json) || (is_string($json) && ctype_digit($json)) ? (int) $json : null;
    }

    /**
     * @param  list<mixed>  $items
     * @return list<array<string, mixed>>
     */
    private static function onlyArrays(array $items): array
    {
        return array_values(array_filter($items, fn (mixed $item) => is_array($item)));
    }

    private static function requestLine(Response $response): string
    {
        $pendingRequest = $response->getPendingRequest();

        return $pendingRequest->getMethod()->value.' '.(parse_url($pendingRequest->getUrl(), PHP_URL_PATH) ?: '/');
    }
}
