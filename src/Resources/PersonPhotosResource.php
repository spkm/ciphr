<?php

namespace spkm\Ciphr\Resources;

use spkm\Ciphr\CiphrConnector;
use spkm\Ciphr\Data\PersonPhoto;
use spkm\Ciphr\Query\Builder;
use spkm\Ciphr\Query\Query;
use spkm\Ciphr\Requests\ItemRequest;
use spkm\Ciphr\Requests\ListRequest;
use spkm\Ciphr\Requests\People\GetPersonPhotoRequest;
use spkm\Ciphr\Requests\People\GetPersonPhotosRequest;

/**
 * PersonPhoto records, from Ciphr::personPhotos().
 *
 * @extends Builder<PersonPhoto>
 */
class PersonPhotosResource extends Builder
{
    public function __construct(CiphrConnector $connector)
    {
        parent::__construct($connector, PersonPhoto::class, 'PersonPhoto', 'ID');
    }

    protected function listRequest(Query $query): ListRequest
    {
        return new GetPersonPhotosRequest($query);
    }

    protected function itemRequest(int $id): ItemRequest
    {
        return new GetPersonPhotoRequest($id);
    }
}
