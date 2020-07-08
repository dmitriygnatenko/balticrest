<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Entity\Point;
use Doctrine\ORM\EntityManagerInterface;

class PointDataProvider implements PointDataProviderInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsByCityAndCategory(string $city, string $category): array
    {
        return $this->em->getRepository(Point::class)->getPointsByCityAndCategory($city, $category);
    }
}