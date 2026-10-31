<?php

namespace spkm\Ciphr\Resources;

use Saloon\Http\Request;
use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\Training;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\Training\CreateTrainingRecordRequest;
use spkm\Ciphr\Requests\Training\GetTrainingRecordRequest;
use spkm\Ciphr\Requests\Training\GetTrainingRecordsRequest;
use spkm\Ciphr\Requests\Training\UpdateTrainingRecordRequest;

/**
 * Training records, from Ciphr::training().
 *
 * @extends Builder<Training>
 */
class TrainingResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, Training::class, 'Training', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetTrainingRecordsRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetTrainingRecordRequest($id);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function createRequest(array $body): Request
    {
        return new CreateTrainingRecordRequest($body);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    protected function updateRequest(int $id, array $body): Request
    {
        return new UpdateTrainingRecordRequest($id, $body);
    }
}
