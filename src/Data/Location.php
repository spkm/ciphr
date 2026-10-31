<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A Location record.
 */
readonly class Location extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $AddLine1 = null,
        public ?string $AddLine2 = null,
        public ?string $AddLine3 = null,
        public ?string $AddLine4 = null,
        public ?string $AddPostcode = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?string $Code = null,
        public ?int $CountryID = null,
        public ?string $Description = null,
        public ?int $PublicHolidayTypeID = null,
    ) {}
}
