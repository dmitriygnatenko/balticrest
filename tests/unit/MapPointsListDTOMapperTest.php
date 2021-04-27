<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Balticrest\Service\Mapper\MapPointsListDTOMapper;
use App\Entity\City;
use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use App\Entity\PointType;
use App\Tests\UnitTester;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class MapPointsListDTOMapperTest extends Unit
{
    /** @var UnitTester */
    protected $tester;

    private MapPointsListDTOMapper $mapper;

    private EntityManagerInterface $entityManager;

    protected function _before()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $this->entityManager = $symfony->grabService(EntityManagerInterface::class);
        $this->mapper = $symfony->grabService(MapPointsListDTOMapper::class);
    }

    public function testMapper()
    {
        $this->tester->wantToTest('MapPointsListDTOMapper');

        $pointRuData = (new PointLangData())
            ->setTitle('Тестовый объект')
            ->setLanguage($this->entityManager->getRepository(Language::class)->findOneByCode('ru'));

        $point = (new Point())
            ->setIsActive(true)
            ->setUrl('testurl')
            ->setLat('1')
            ->setLon('1')
            ->setType($this->entityManager->getRepository(PointType::class)->findOneByCode('hotels'))
            ->setCity($this->entityManager->getRepository(City::class)->findOneByCode('svetlogorsk'))
            ->addPointLangData($pointRuData);

        $dto = $this->mapper->fill('zelenogradsk', [$point]);

        $this->assertEquals(
            $dto->getJsonResult(),
            '{"center":{"lat":54.957757,"lon":20.474495},"trans":{"point_button":"\u041f\u043e\u0434\u0440\u043e\u0431\u043d\u0435\u0435"},"zoom":15,"points":[{"lat":1,"lon":1,"hint":"\u0422\u0435\u0441\u0442\u043e\u0432\u044b\u0439 \u043e\u0431\u044a\u0435\u043a\u0442","title":"\u0422\u0435\u0441\u0442\u043e\u0432\u044b\u0439 \u043e\u0431\u044a\u0435\u043a\u0442","description":"","image":"\/static\/balticrest\/images\/logo\/hotels.png","link":"http:\/\/localhost\/svetlogorsk\/hotels\/point\/testurl","icon":{"image":"\/static\/balticrest\/images\/markers\/hotels.png","size":{"width":27,"height":48}}}]}'
        );
    }
}
