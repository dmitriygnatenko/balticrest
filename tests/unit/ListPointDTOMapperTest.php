<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Balticrest\Service\Mapper\ListPointDTOMapper;
use App\Entity\City;
use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use App\Entity\PointType;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class ListPointDTOMapperTest extends Unit
{
    /** @var string */
    private const TEST_TITLE = 'Тестовый объект';

    private ListPointDTOMapper $mapper;

    private EntityManagerInterface $entityManager;

    protected function _before()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $this->entityManager = $symfony->grabService(EntityManagerInterface::class);
        $this->mapper = $symfony->grabService(ListPointDTOMapper::class);
    }

    public function testMapper()
    {
        $pointRuData = (new PointLangData())
            ->setTitle(self::TEST_TITLE)
            ->setLanguage($this->entityManager->getRepository(Language::class)->findOneByCode('ru'));

        $point = (new Point())
            ->setIsActive(true)
            ->setUrl('testurl')
            ->setType($this->entityManager->getRepository(PointType::class)->findOneByCode('hotels'))
            ->setCity($this->entityManager->getRepository(City::class)->findOneByCode('svetlogorsk'))
            ->addPointLangData($pointRuData);

        $dto = $this->mapper->fill($point);

        $this->assertEquals($dto->getTitle(), self::TEST_TITLE);
        $this->assertEquals($dto->getImage(), '/static/balticrest/images/logo/hotels.png');
        $this->assertStringContainsString($dto->getUrl(), 'svetlogorsk/hotels/point/testurl');
    }
}
