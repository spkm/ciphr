<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A JobDetail record.
 */
readonly class JobDetail extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?string $CeasedReason = null,
        public ?string $Description = null,
        public ?int $JobClassificationID = null,
        public ?int $JobStatusID = null,
        public ?int $LocationID = null,
        public ?int $ReportsToJobID = null,
        public ?string $Title = null,
    ) {}
}
