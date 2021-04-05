<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

interface TransliteratorHelperInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function transliterate(string $string): string;
}