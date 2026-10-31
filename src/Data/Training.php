<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A Training record.
 */
readonly class Training extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?int $CourseID = null,
        public ?float $CPD = null,
        public ?int $CurrencyID = null,
        public ?CarbonImmutable $EndDate = null,
        public ?int $Evaluation = null,
        public ?int $EventsDelegateStatusID = null,
        public ?int $EventsID = null,
        public ?float $Expenses = null,
        public ?bool $ExpReimbursed = null,
        public ?string $ExternalID = null,
        public ?float $Fees = null,
        public ?bool $FeesReimbursed = null,
        public ?CarbonImmutable $HotelDateFrom = null,
        public ?int $HotelID = null,
        public ?int $HotelNights = null,
        public ?string $InvoiceAddressLine1 = null,
        public ?string $InvoiceAddressLine2 = null,
        public ?string $InvoiceAddressLine3 = null,
        public ?string $InvoiceAddressLine4 = null,
        public ?string $InvoiceName = null,
        public ?string $InvoicePostcode = null,
        public ?int $MentorPersrecID = null,
        public ?float $Misc = null,
        public ?string $MiscDescription = null,
        public ?bool $MiscReimbursed = null,
        public ?string $Notes = null,
        public ?int $OrgUnit1ID = null,
        public ?int $PerID = null,
        public ?string $PurchaseNo = null,
        public ?int $QuestionSetID = null,
        public ?CarbonImmutable $StartDate = null,
        public ?string $Supplier = null,
        public ?int $SupplierID = null,
        public ?string $Title = null,
        public ?float $TotalCost = null,
        public ?float $TotalDays = null,
        public ?float $TotalHours = null,
        public ?int $TrainingMethodID = null,
        public ?int $TrainingTypeID = null,
        public ?string $Venue = null,
        public ?int $VenueID = null,
        public ?CarbonImmutable $WithdrawnDate = null,
        public ?string $WithdrawnReason = null,
    ) {}
}
