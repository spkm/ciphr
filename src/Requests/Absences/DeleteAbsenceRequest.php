<?php

namespace spkm\Ciphr\Requests\Absences;

use Saloon\Enums\Method;
use spkm\Ciphr\Requests\CiphrRequest;

/**
 * Delete a Absence record: DELETE /Absence/{id}.
 */
class DeleteAbsenceRequest extends CiphrRequest
{
    protected Method $method = Method::DELETE;

    protected bool $safe = false;

    public function __construct(protected int $id) {}

    public function resolveEndpoint(): string
    {
        return "/Absence/{$this->id}";
    }
}
