<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Sitemap;

interface SitemapGeneratorInterface
{
    /**
     * @return string
     */
    public function generateContent(): string;
}