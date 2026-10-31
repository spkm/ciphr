<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A AbsenceDetail record.
 */
readonly class AbsenceDetail extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?int $AbsenceID = null,
        public ?float $Days = null,
        public ?CarbonImmutable $End = null,
        public ?float $Hours = null,
        public ?float $MinsLate = null,
        public ?float $OSPPercent = null,
        public ?float $OSPPreviousPercent = null,
        public ?CarbonImmutable $Start = null,
    ) {}
}
