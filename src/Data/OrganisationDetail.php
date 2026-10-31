<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A OrganisationDetail record.
 */
readonly class OrganisationDetail extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?bool $Ceased = null,
        public ?CarbonImmutable $ClientFieldDate1 = null,
        public ?CarbonImmutable $ClientFieldDate10 = null,
        public ?CarbonImmutable $ClientFieldDate11 = null,
        public ?CarbonImmutable $ClientFieldDate12 = null,
        public ?CarbonImmutable $ClientFieldDate13 = null,
        public ?CarbonImmutable $ClientFieldDate14 = null,
        public ?CarbonImmutable $ClientFieldDate15 = null,
        public ?CarbonImmutable $ClientFieldDate16 = null,
        public ?CarbonImmutable $ClientFieldDate17 = null,
        public ?CarbonImmutable $ClientFieldDate18 = null,
        public ?CarbonImmutable $ClientFieldDate19 = null,
        public ?CarbonImmutable $ClientFieldDate2 = null,
        public ?CarbonImmutable $ClientFieldDate20 = null,
        public ?CarbonImmutable $ClientFieldDate3 = null,
        public ?CarbonImmutable $ClientFieldDate4 = null,
        public ?CarbonImmutable $ClientFieldDate5 = null,
        public ?CarbonImmutable $ClientFieldDate6 = null,
        public ?CarbonImmutable $ClientFieldDate7 = null,
        public ?CarbonImmutable $ClientFieldDate8 = null,
    ) {}
}
