<?php

namespace spkm\Ciphr\Requests;

use Saloon\Enums\Method;

/**
 * A GET for one record by its id.
 */
abstract class ItemRequest extends CiphrRequest
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::ONE;

    protected bool $cacheable = true;

    public function __construct(protected int $id) {}
}
