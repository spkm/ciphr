<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A PersonQualification record.
 */
readonly class PersonQualification extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $Date = null,
        public ?bool $MoreSubjects = null,
        public ?int $PerID = null,
        public ?string $PersonName = null,
        public ?int $QualCatID = null,
        public ?string $QualificationCategoryDescription = null,
        public ?string $QualificationLevelDescription = null,
        public ?float $QualificationLevelRank = null,
        public ?string $QualificationSubjectCode = null,
        public ?string $QualificationSubjectDescription = null,
        public ?int $QualLevelID = null,
        public ?int $QualSubjectID = null,
    ) {}
}
