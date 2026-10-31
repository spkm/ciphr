<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A Title record.
 */
readonly class Title extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?string $Name = null,
        public ?string $Sex = null,
    ) {}
}
