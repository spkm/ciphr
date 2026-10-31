<?php

namespace spkm\Ciphr\Data;

/**
 * A AuditData record.
 */
readonly class AuditData extends Data
{
    public function __construct(
        public ?int $AuditLogID = null,
        public ?string $NewData = null,
        public ?string $OldData = null,
        public ?string $SQLFieldName = null,
    ) {}
}
