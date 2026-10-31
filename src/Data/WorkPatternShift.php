<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A WorkPatternShift record.
 */
readonly class WorkPatternShift extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?float $BreakLength = null,
        public ?CarbonImmutable $CeasedDate = null,
        public ?int $DaysFromBase = null,
        public ?CarbonImmutable $EndTime = null,
        public ?float $Hours = null,
        public ?CarbonImmutable $StartTime = null,
        public ?int $WorkPatternID = null,
    ) {}
}
