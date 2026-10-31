<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A WorkPattern record.
 */
readonly class WorkPattern extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?string $Description = null,
    ) {}
}
