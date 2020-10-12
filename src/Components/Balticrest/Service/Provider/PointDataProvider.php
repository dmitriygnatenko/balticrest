<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Entity\Interfaces\PointDataFieldsInterface as PointFields;
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

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsWithUrlByCityAndCategory(string $city, string $category): array
    {
        return $this->em->getRepository(Point::class)->getPointsWithUrlByCityAndCategory($city, $category);
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointImage(Point $point): string
    {
        $pointLogo = $point->getLogo();

        if ($pointLogo) {
            return $pointLogo;
        }

        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'logo/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'logo/'  . $point->getType()->getCode() . '.png';
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointIconImage(Point $point): string
    {
        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'markers/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'markers/'  . $point->getType()->getCode() . '.png';
    }
}