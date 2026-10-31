<?php

namespace spkm\Ciphr\Data;

/**
 * A OrgChartDetail record.
 */
readonly class OrgChartDetail extends Data
{
    public function __construct(
        public ?string $JsonTable = null,
    ) {}

    /**
     * The org chart Ciphr sends as a JSON string, decoded.
     *
     * @return array<mixed>
     */
    public function table(): array
    {
        $table = is_string($this->JsonTable) ? json_decode($this->JsonTable, true) : null;

        return is_array($table) ? $table : [];
    }
}
