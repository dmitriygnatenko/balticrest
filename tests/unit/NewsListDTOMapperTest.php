<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Balticrest\Service\Mapper\NewsListDTOMapper;
use App\Entity\News;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class NewsListDTOMapperTest extends Unit
{
    private NewsListDTOMapper $mapper;

    private EntityManagerInterface $entityManager;

    protected function _before()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $this->entityManager = $symfony->grabService(EntityManagerInterface::class);
        $this->mapper = $symfony->grabService(NewsListDTOMapper::class);
    }

    public function testMapper()
    {
        $news = $this->entityManager->getRepository(News::class)->getActivePaginatedNews();
        $dto = $this->mapper->fill($news);

        $this->assertNotEmpty($dto->getNews());
    }
}
