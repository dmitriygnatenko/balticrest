<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Admin\Service\DTO\PointDTO;
use App\Components\Admin\Service\Mapper\PointDTOMapper;
use App\Entity\City;
use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use App\Entity\PointType;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class PointDTOMapperTest extends Unit
{
    /** @var string */
    private const TEST_TITLE = 'Тестовый объект';

    private PointDTOMapper $mapper;

    private EntityManagerInterface $entityManager;

    protected function _before()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $this->entityManager = $symfony->grabService(EntityManagerInterface::class);
        $this->mapper = $symfony->grabService(PointDTOMapper::class);
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

        $dto = $this->mapper->fill($point, new PointDTO());

        $this->assertEquals($dto->getTitle(), self::TEST_TITLE);
        $this->assertEquals($dto->getCity(), 'svetlogorsk');
        $this->assertEquals($dto->getType(), 'hotels');
        $this->assertEquals($dto->getIsActive(), '1');
    }
}
