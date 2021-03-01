<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Factory;

use elFinder;

interface ElfinderFactoryInterface
{
    /** @var string */
    public const DRIVER = 'LocalFileSystem';

    /** @var string */
    public const PATH = 'files';

    /**
     * @return elFinder
     */
    public function create(): elFinder;
}