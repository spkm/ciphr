<?php

namespace spkm\Ciphr\Data;

/**
 * A QualificationCategory record.
 */
readonly class QualificationCategory extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?string $Description = null,
    ) {}
}
