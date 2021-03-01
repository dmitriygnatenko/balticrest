<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Factory;

use elFinder;

class ElfinderFactory implements ElfinderFactoryInterface
{
    /**
     * @return elFinder
     */
    public function create(): elFinder
    {
        $opts = [
            'roots' => [
                [
                    'driver' => self::DRIVER,
                    'path' => self::PATH . '/',
                    'URL' => '/' . self::PATH . '/',
                ],
            ]
        ];

        return new elFinder($opts);
    }
}