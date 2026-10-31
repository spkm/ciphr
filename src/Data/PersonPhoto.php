<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A PersonPhoto record.
 */
readonly class PersonPhoto extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $Name = null,
        public ?string $Photo = null,
        public ?CarbonImmutable $PhotoDate = null,
        public ?string $PhotoType = null,
        public ?string $PhotoUrl = null,
    ) {}
}
