<?php

namespace spkm\ciphr\Traits;

trait CiphrPaginationDefaults
{
    public function setPaginationSortKey(string $defaultKey, array $queryParameters): void
    {
        if (array_key_exists('s', $queryParameters)) {
            $this->config()->set(['paginationSortKey' => $queryParameters['s']]);
        } else {
            $this->config()->set(['paginationSortKey' => $defaultKey]);
        }
    }
}