<?php

namespace spkm\Ciphr\Resources;

use Illuminate\Support\Collection;
use Saloon\Http\BaseResource;
use spkm\Ciphr\Data\OrgChartDetail;
use spkm\Ciphr\Requests\Organisation\GetOrgChartDetailRequest;

/**
 * The organisation charts, from Ciphr::orgChart()->get().
 */
class OrgChartResource extends BaseResource
{
    /**
     * Every chart Ciphr holds, each with its JSON table decoded by OrgChartDetail::table().
     *
     * @return Collection<int, OrgChartDetail>
     */
    public function get(): Collection
    {
        return $this->connector->send(new GetOrgChartDetailRequest)->dto();
    }
}
