<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Search;

interface SearchDataUpdaterInterface
{
    public function update(): void;
}