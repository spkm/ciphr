<?php

namespace spkm\Ciphr\Data;

/**
 * A AuditLog record.
 */
readonly class AuditLog extends Data
{
    public function __construct(
        public ?int $ID = null,
    ) {}
}
