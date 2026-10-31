<?php

namespace spkm\Ciphr\Requests\Organisation;

use Saloon\Enums\Method;
use spkm\Ciphr\Data\OrgChartDetail;
use spkm\Ciphr\Requests\CiphrRequest;

/**
 * Every OrgChartDetail record: GET /OrgChartDetail.
 */
class GetOrgChartDetailRequest extends CiphrRequest
{
    protected Method $method = Method::GET;

    protected string $responseShape = self::LIST;

    protected bool $cacheable = true;

    public string $resultKey = 'OrgChartDetail';

    protected ?string $dto = OrgChartDetail::class;

    public function resolveEndpoint(): string
    {
        return '/OrgChartDetail';
    }
}
