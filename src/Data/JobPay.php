<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A JobPay record.
 */
readonly class JobPay extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?float $AnnualPay = null,
        public ?float $BasicPay = null,
        public ?int $ChangeNo = null,
        public ?int $ChangeReasonID = null,
        public ?CarbonImmutable $ContractEnd = null,
        public ?int $ContractID = null,
        public ?int $CostCentreAID = null,
        public ?float $CostCentreAPercent = null,
        public ?int $CostCentreBID = null,
        public ?float $CostCentreBPercent = null,
        public ?int $CurrencyID = null,
        public ?CarbonImmutable $DateFrom = null,
        public ?CarbonImmutable $DateTo = null,
        public ?CarbonImmutable $DateToGrade = null,
        public ?float $FTE = null,
        public ?string $FTECalcMethod = null,
        public ?int $GradeID = null,
        public ?float $HomeCurrencyBasicPay = null,
        public ?float $HourlyRate = null,
        public ?float $Hours = null,
        public ?float $IncreaseAmount = null,
        public ?float $IncreasePercent = null,
        public ?bool $IncrementRun = null,
        public ?bool $JobChange = null,
        public ?int $JobClassificationID = null,
        public ?CarbonImmutable $JobEndDate = null,
        public ?int $JobID = null,
        public ?CarbonImmutable $JobStartDate = null,
        public ?string $JobTitle = null,
        public ?bool $Leaver = null,
        public ?int $LocationID = null,
        public ?string $MultiEntityDescription = null,
        public ?int $MultiEntityID = null,
        public ?CarbonImmutable $NextIncrementDate = null,
        public ?string $Notes = null,
        public ?float $OnCallAmount = null,
        public ?int $OrgUnit1ID = null,
        public ?int $PayBasisID = null,
        public ?int $PayFreqID = null,
        public ?int $PerID = null,
        public ?int $SpineID = null,
        public ?float $SpinePoint = null,
        public ?bool $Starter = null,
        public ?string $TermsConditionsDescription = null,
        public ?int $TermsConditionsID = null,
        public ?int $TimesheetRuleID = null,
        public ?float $WeeksInYear = null,
        public ?int $WorkPatternID = null,
    ) {}
}
