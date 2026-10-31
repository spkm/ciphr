<?php

namespace spkm\Ciphr\Tests\Fixtures;

use spkm\Ciphr\Data\Data;

readonly class ExampleDetail extends Data
{
    public function __construct(
        public ?int $ID = null,
        public ?float $Days = null,
    ) {}
}
