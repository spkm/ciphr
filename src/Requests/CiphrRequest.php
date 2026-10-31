<?php

namespace spkm\Ciphr\Requests;

use Illuminate\Support\Facades\Cache;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Saloon\CachePlugin\Contracts\Cacheable;
use Saloon\CachePlugin\Contracts\Driver;
use Saloon\CachePlugin\Drivers\LaravelCacheDriver;
use Saloon\CachePlugin\Traits\HasCaching;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use spkm\Ciphr\CiphrApiException;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Exceptions\ServerErrorException;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Support\Payload;

/**
 * What every request to Ciphr shares: the shape of the response and the model it maps to (for dto()), optional
 * response caching, and the query-string fixes Ciphr's grammar needs.
 */
abstract class CiphrRequest extends Request implements Cacheable
{
    use HasCaching;

    public const ONE = 'one';

    public const LIST = 'list';

    public const RAW = 'raw';

    public const NONE = 'none';

    /**
     * The name Ciphr wraps a list of this entity in, such as "PersonDetail".
     */
    public string $resultKey = '';

    protected string $responseShape = self::NONE;

    /** @var class-string<Data>|null */
    protected ?string $dto = null;

    protected bool $cacheable = false;

    protected bool $safe = true;

    public function resolveCacheDriver(): Driver
    {
        return new LaravelCacheDriver(Cache::store(config('ciphr.cache.store')));
    }

    public function cacheExpiryInSeconds(): int
    {
        return (int) config('ciphr.cache.seconds', 3600);
    }

    /**
     * @return array<Method>
     */
    protected function getCacheableMethods(): array
    {
        return $this->cacheable && config('ciphr.cache.enabled') ? [Method::GET] : [];
    }

    protected function cacheKey(PendingRequest $pendingRequest): string
    {
        $connector = $pendingRequest->getConnector();
        $portal = $connector instanceof CiphrConnector ? $connector->portal() : '';

        return 'ciphr.response.'.sha1($portal.' '.$pendingRequest->getMethod()->value.' '.$pendingRequest->getUri());
    }

    /**
     * Ciphr's flags are bare names (?count, ?first, ?GetChangesFrom): drop the "=" that building the query adds.
     */
    public function handlePsrRequest(RequestInterface $request, PendingRequest $pendingRequest): RequestInterface
    {
        $query = (string) preg_replace('/(^|&)(first|last|count|GetChangesFrom)=(?=&|$)/', '$1$2', $request->getUri()->getQuery());

        return $request->withUri($request->getUri()->withQuery($query));
    }

    /**
     * Whether the request can be retried: a GET that changes nothing.
     */
    public function isSafe(): bool
    {
        return $this->safe && $this->getMethod() === Method::GET;
    }

    /**
     * @return class-string<Data>|null
     */
    public function dtoClass(): ?string
    {
        return $this->dto;
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        if (! $response->successful()) {
            throw CiphrApiException::fromResponse($response);
        }

        try {
            return match ($this->responseShape) {
                self::ONE => Payload::object($response, $this->resultKey, $this->dto),
                self::LIST => $response->getPendingRequest()->query()->get(Query::COUNT) !== null
                    ? Payload::count($response)
                    : Payload::items($response, $this->resultKey, $this->dto),
                self::RAW => Payload::json($response),
                default => null,
            };
        } catch (JsonException) {
            // A successful status with something other than JSON, such as a proxy's or maintenance page.
            throw new ServerErrorException($response);
        }
    }
}
