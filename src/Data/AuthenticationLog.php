<?php

namespace spkm\Ciphr\Data;

use Carbon\CarbonImmutable;

/**
 * A AuthenticationLog record.
 */
readonly class AuthenticationLog extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?CarbonImmutable $DateTimeStamp = null,
        public ?string $Detail = null,
        public ?string $IPAddress = null,
        public ?string $RequestPath = null,
        public ?bool $Successful = null,
        public ?string $Username = null,
    ) {}
}
