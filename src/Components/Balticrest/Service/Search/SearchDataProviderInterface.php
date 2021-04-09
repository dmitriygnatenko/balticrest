<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Search;

interface SearchDataProviderInterface
{
    /**
     * @param string $searchText
     *
     * @return array
     */
    public function search(string $searchText): array;
}