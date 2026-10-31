<?php

namespace spkm\Ciphr\Data;

/**
 * A QualificationLevel record.
 */
readonly class QualificationLevel extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $Description = null,
        public ?string $QualCatDescription = null,
        public ?int $QualCatID = null,
    ) {}
}
