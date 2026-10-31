<?php

namespace spkm\Ciphr\Tests\Fixtures;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use spkm\Ciphr\Data\Data;

readonly class ExampleData extends Data
{
    protected const LISTS = ['Details' => ExampleDetail::class];

    /**
     * @param  Collection<int, ExampleDetail>  $Details
     */
    public function __construct(
        public ?int $ID = null,
        public ?string $Name = null,
        public ?bool $Active = null,
        public ?float $Hours = null,
        public ?CarbonImmutable $StartDate = null,
        public Collection $Details = new Collection,
    ) {}
}
