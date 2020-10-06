<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\DTO\ArticleDTO;

interface ArticleDataProviderInterface
{
    /**
     * @param string $url
     *
     * @return ArticleDTO|null
     */
    public function getCachedArticleData(string $url): ?ArticleDTO;

    /**
     * @param string $url
     *
     * @return ArticleDTO|null
     */
    public function getArticleData(string $url): ?ArticleDTO;
}