<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\DTO\NewsListDTO;

interface NewsDataProviderInterface
{
    /** @var int */
    public const NEWS_PER_PAGE = 10;

    /**
     * @param int $page
     * @param string|null $tag
     *
     * @return NewsListDTO
     */
    public function getCachedData(int $page = 1, ?string $tag = null): NewsListDTO;

    /**
     * @param int $page
     * @param string|null $tag
     *
     * @return NewsListDTO
     */
    public function getData(int $page = 1, ?string $tag = null): NewsListDTO;
}