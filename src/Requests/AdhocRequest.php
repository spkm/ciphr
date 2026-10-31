<?php

namespace spkm\Ciphr\Requests;

use Saloon\Enums\Method;

/**
 * A GET or DELETE to a path of your choosing, for Ciphr::get() and delete(). The response's dto() is the decoded
 * JSON. Ciphr's gateway refuses a GET that carries a body, so this request never sends one; AdhocBodyRequest does.
 */
class AdhocRequest extends CiphrRequest
{
    protected string $responseShape = self::RAW;

    /**
     * @param  array<string, mixed>  $queryParameters
     */
    public function __construct(
        protected Method $method,
        protected string $path,
        protected array $queryParameters = [],
    ) {
        $this->safe = $method === Method::GET;
    }

    public function resolveEndpoint(): string
    {
        return $this->path;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryParameters;
    }
}
