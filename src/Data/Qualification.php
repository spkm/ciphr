<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A Qualification record.
 */
readonly class Qualification extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $ClientFieldDate1 = null,
        public ?CarbonImmutable $ClientFieldDate10 = null,
        public ?CarbonImmutable $ClientFieldDate11 = null,
        public ?CarbonImmutable $ClientFieldDate12 = null,
        public ?CarbonImmutable $ClientFieldDate13 = null,
        public ?CarbonImmutable $ClientFieldDate14 = null,
        public ?CarbonImmutable $ClientFieldDate15 = null,
        public ?CarbonImmutable $ClientFieldDate16 = null,
        public ?CarbonImmutable $ClientFieldDate17 = null,
        public ?CarbonImmutable $ClientFieldDate18 = null,
        public ?CarbonImmutable $ClientFieldDate19 = null,
        public ?CarbonImmutable $ClientFieldDate2 = null,
        public ?CarbonImmutable $ClientFieldDate20 = null,
        public ?CarbonImmutable $ClientFieldDate3 = null,
        public ?CarbonImmutable $ClientFieldDate4 = null,
        public ?CarbonImmutable $ClientFieldDate5 = null,
        public ?CarbonImmutable $ClientFieldDate6 = null,
        public ?CarbonImmutable $ClientFieldDate7 = null,
        public ?CarbonImmutable $ClientFieldDate8 = null,
        public ?CarbonImmutable $ClientFieldDate9 = null,
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
