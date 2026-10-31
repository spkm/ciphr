<?php

namespace spkm\Ciphr\Requests\Absences;

use spkm\Ciphr\Data\AbsenceDetail;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;

/**
 * The AbsenceDetail records under the path: GET /AbsenceDetail/{absenceId}.
 */
class GetAbsenceDetailsForAbsenceRequest extends ListRequest
{
    public string $resultKey = 'AbsenceDetail';

    protected ?string $dto = AbsenceDetail::class;

    /**
     * @param  Query|array<string, mixed>  $query
     */
    public function __construct(protected int $absenceId, Query|array $query = [])
    {
        parent::__construct($query);
    }

    public function resolveEndpoint(): string
    {
        return "/AbsenceDetail/{$this->absenceId}";
    }
}
