<?php

declare(strict_types=1);

namespace App\Tests;

use App\Components\Balticrest\Service\Mapper\ArticleDTOMapper;
use App\Entity\Article;
use App\Entity\ArticleLangData;
use App\Entity\Language;
use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class ArticleDTOMapperTest extends Unit
{
    /** @var string */
    private const TEST_TITLE = 'Тестовая статья';

    /** @var string */
    private const TEST_TEXT = 'Текст тестовой статьи';

    private ArticleDTOMapper $mapper;

    private EntityManagerInterface $entityManager;
    
    protected function _before()
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        $this->entityManager = $symfony->grabService(EntityManagerInterface::class);
        $this->mapper = $symfony->grabService(ArticleDTOMapper::class);
    }

    public function testMapper()
    {
        $articleRuData = (new ArticleLangData())
            ->setTitle(self::TEST_TITLE)
            ->setText(self::TEST_TEXT)
            ->setLanguage($this->entityManager->getRepository(Language::class)->findOneByCode('ru'))
            ->setMetaKeywords('')
            ->setMetaDescription('');

        $article = (new Article())
            ->setIsActive(true)
            ->setUrl('testurl')
            ->addArticleLangData($articleRuData);

        $dto = $this->mapper->fill($article);

        $this->assertEquals($dto->getTitle(), self::TEST_TITLE);
        $this->assertEquals($dto->getText(), self::TEST_TEXT);
    }
}
