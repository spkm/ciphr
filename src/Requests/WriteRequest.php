<?php

namespace spkm\Ciphr\Requests;

use JsonSerializable;
use Saloon\Contracts\Body\HasBody;
use Saloon\Traits\Body\HasJsonBody;

/**
 * A POST or PUT carrying a record as JSON: an array of Ciphr's property names, or a model.
 */
abstract class WriteRequest extends CiphrRequest implements HasBody
{
    use HasJsonBody;

    protected bool $safe = false;

    /** @var array<string, mixed>|JsonSerializable */
    protected array|JsonSerializable $record;

    /**
     * @param  array<string, mixed>|JsonSerializable  $body
     */
    public function __construct(array|JsonSerializable $body)
    {
        $this->record = $body;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return $this->record instanceof JsonSerializable ? (array) $this->record->jsonSerialize() : $this->record;
    }
}
