<?php

namespace spkm\Ciphr\Tests\Fixtures;

use Saloon\Enums\Method;
use spkm\Ciphr\Requests\CiphrRequest;

class DeleteExampleRequest extends CiphrRequest
{
    protected Method $method = Method::DELETE;

    protected bool $safe = false;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/Example/{$this->id}";
    }
}
