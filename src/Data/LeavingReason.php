<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A LeavingReason record.
 */
readonly class LeavingReason extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $Category = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?string $Code = null,
        public ?string $Description = null,
    ) {}
}
