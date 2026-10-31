<?php

namespace spkm\Ciphr\Tests\Fixtures;

use JsonSerializable;
use Saloon\Enums\Method;
use spkm\Ciphr\Requests\WriteRequest;

class UpdateExampleRequest extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected string $responseShape = self::ONE;

    public string $resultKey = 'Example';

    protected ?string $dto = ExampleData::class;

    /**
     * @param  array<string, mixed>|JsonSerializable  $body
     */
    public function __construct(protected int $id, array|JsonSerializable $body)
    {
        parent::__construct($body);
    }

    protected function defaultBody(): array
    {
        return ['ID' => $this->id, ...parent::defaultBody()];
    }

    public function resolveEndpoint(): string
    {
        return "/Example/{$this->id}";
    }
}
