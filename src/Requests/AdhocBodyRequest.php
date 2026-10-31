<?php

namespace spkm\Ciphr\Requests;

use JsonSerializable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

/**
 * A POST or PUT to a path of your choosing with a JSON body, for Ciphr::post() and put().
 */
class AdhocBodyRequest extends AdhocRequest implements HasBody
{
    use HasJsonBody;

    /**
     * @param  array<string, mixed>  $queryParameters
     * @param  array<mixed>|JsonSerializable  $payload
     */
    public function __construct(
        Method $method,
        string $path,
        protected array|JsonSerializable $payload = [],
        array $queryParameters = [],
    ) {
        parent::__construct($method, $path, $queryParameters);
    }

    /**
     * @return array<mixed>
     */
    protected function defaultBody(): array
    {
        return $this->payload instanceof JsonSerializable ? (array) $this->payload->jsonSerialize() : $this->payload;
    }
}
