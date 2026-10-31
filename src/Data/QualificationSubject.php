<?php

namespace spkm\Ciphr\Data;

/**
 * A QualificationSubject record.
 */
readonly class QualificationSubject extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $Code = null,
        public ?string $Description = null,
        public ?string $QualCatDescription = null,
        public ?int $QualCatID = null,
    ) {}
}
