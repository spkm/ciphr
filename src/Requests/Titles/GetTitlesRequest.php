<?php

namespace spkm\Ciphr\Requests\Titles;

use spkm\Ciphr\Data\Title;
use spkm\Ciphr\Requests\ListRequest;

/**
 * Every Title record: GET /Title.
 */
class GetTitlesRequest extends ListRequest
{
    public string $resultKey = 'Title';

    protected ?string $dto = Title::class;

    public function resolveEndpoint(): string
    {
        return '/Title';
    }
}
