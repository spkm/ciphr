<?php

namespace spkm\Ciphr\Resources;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Absence;
use spkm\Ciphr\Exceptions\NotFoundException;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\Absences\CreateAbsenceRequest;
use spkm\Ciphr\Requests\Absences\DeleteAbsenceRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceRequest;
use spkm\Ciphr\Requests\Absences\GetAbsencesRequest;
use spkm\Ciphr\Requests\Absences\GetAbsenceWithDetailRequest;
use spkm\Ciphr\Requests\Absences\UpdateAbsenceRequest;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Absence records, from Ciphr::absences().
 *
 * @extends Builder<Absence>
 */
class AbsencesResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, Absence::class, 'Absence', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetAbsencesRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetAbsenceRequest($id);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function createRequest(array $body): Request
    {
        return new CreateAbsenceRequest($body);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): Request
    {
        return new UpdateAbsenceRequest($id, $body);
    }

    protected function deleteRequest(int $id): Request
    {
        return new DeleteAbsenceRequest($id);
    }

    /**
     * An absence with its detail rows, or null when Ciphr has none with that id.
     */
    public function withDetail(int $id): ?Absence
    {
        try {
            return $this->fetch(new GetAbsenceWithDetailRequest($id));
        } catch (NotFoundException) {
            return null;
        }
    }

    /**
     * The detail rows of every absence.
     */
    public function details(): AbsenceDetailsResource
    {
        return new AbsenceDetailsResource($this->connector);
    }
}
