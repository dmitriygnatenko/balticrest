<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use Transliterator;

class TransliteratorHelper implements TransliteratorHelperInterface
{
    /**
     * @param string $string
     *
     * @return string
     */
    public function transliterate(string $string): string
    {
        return Transliterator::create("Any-Latin; Latin-ASCII")->transliterate($string);
    }
}