<?php

namespace spkm\Ciphr;

use JsonSerializable;
use Saloon\Config;
use Saloon\Enums\Method;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\Traits\Plugins\HasTimeout;
use spkm\Ciphr\Data\Data;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Requests\AdhocBodyRequest;
use spkm\Ciphr\Requests\AdhocRequest;
use spkm\Ciphr\Requests\CiphrRequest;
use spkm\Ciphr\Resources\AdhocResource;
use spkm\Ciphr\Resources\HasResources;

/**
 * The connection to one Ciphr portal: its API key, timeouts and retries, with a resource for every entity Ciphr
 * documents and ad-hoc calls for anything else.
 */
class CiphrConnector extends Connector implements HasPagination
{
    use HasResources;
    use HasTimeout;

    protected float $connectTimeout;

    protected float $requestTimeout;

    public function __construct(protected string $portal, protected string $apiKey, protected ?string $baseUrl = null)
    {
        $this->connectTimeout = (float) config('ciphr.timeouts.connect', Config::$defaultConnectionTimeout);
        $this->requestTimeout = (float) config('ciphr.timeouts.request', Config::$defaultRequestTimeout);

        if (config('ciphr.retries.times')) {
            $this->tries = (int) config('ciphr.retries.times');
            $this->retryInterval = (int) config('ciphr.retries.interval_ms', 500);
            $this->useExponentialBackoff = true;
            $this->throwOnMaxTries = false;
        }
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl ?? "https://{$this->portal}.app.ciphr.com/api";
    }

    public function portal(): string
    {
        return $this->portal;
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->apiKey, 'apikey');
    }

    /**
     * @return array<string, string>
     */
    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Retry only a request that changes nothing, and only for a fault on Ciphr's side or the way there.
     */
    public function handleRetry(FatalRequestException|RequestException $exception, Request $request): bool
    {
        $isSafe = $request instanceof CiphrRequest ? $request->isSafe() : $request->getMethod() === Method::GET;

        if (! $isSafe) {
            return false;
        }

        return $exception instanceof FatalRequestException
            || in_array($exception->getResponse()->status(), [408, 429], true)
            || $exception->getResponse()->serverError();
    }

    /**
     * Page through a list request, $perPage records at a time (ciphr.pagination.per_page by default).
     */
    public function paginate(Request $request, ?int $perPage = null): CiphrPaginator
    {
        return new CiphrPaginator($this, $request, $perPage ?? (int) config('ciphr.pagination.per_page', 100));
    }

    /**
     * A query over an end node the package has no resource for, as Ciphr::from('/NewEntity'); its records are arrays.
     *
     * @param  string  $path  The end node, such as '/NewEntity'.
     * @param  string|null  $resultKey  The name Ciphr wraps the list in; the last segment of the path by default.
     * @param  string  $sortKey  The property to sort by when paging.
     * @return Builder<Data>
     */
    public function from(string $path, ?string $resultKey = null, string $sortKey = 'ID'): Builder
    {
        return new AdhocResource($this, $path, $resultKey ?? basename($path), $sortKey);
    }

    /**
     * @param  array<string, mixed>  $query
     */
    public function get(string $path, array $query = []): Response
    {
        return $this->send(new AdhocRequest(Method::GET, $path, $query));
    }

    /**
     * @param  array<mixed>|JsonSerializable  $body
     * @param  array<string, mixed>  $query
     */
    public function post(string $path, array|JsonSerializable $body = [], array $query = []): Response
    {
        return $this->send(new AdhocBodyRequest(Method::POST, $path, $body, $query));
    }

    /**
     * @param  array<mixed>|JsonSerializable  $body
     * @param  array<string, mixed>  $query
     */
    public function put(string $path, array|JsonSerializable $body = [], array $query = []): Response
    {
        return $this->send(new AdhocBodyRequest(Method::PUT, $path, $body, $query));
    }

    /**
     * @param  array<string, mixed>  $query
     */
    public function delete(string $path, array $query = []): Response
    {
        return $this->send(new AdhocRequest(Method::DELETE, $path, $query));
    }
}
