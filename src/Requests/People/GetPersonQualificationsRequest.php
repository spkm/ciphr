<?php

namespace spkm\Ciphr\Requests\People;

use spkm\Ciphr\Data\PersonQualification;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ListRequest;

/**
 * The PersonQualification records under the path: GET /PersonDetail/{perId}/PersonQualification.
 */
class GetPersonQualificationsRequest extends ListRequest
{
    public string $resultKey = 'PersonQualification';

    protected ?string $dto = PersonQualification::class;

    /**
     * @param  Query|array<string, mixed>  $query
     */
    public function __construct(protected int $perId, Query|array $query = [])
    {
        parent::__construct($query);
    }

    public function resolveEndpoint(): string
    {
        return "/PersonDetail/{$this->perId}/PersonQualification";
    }
}
