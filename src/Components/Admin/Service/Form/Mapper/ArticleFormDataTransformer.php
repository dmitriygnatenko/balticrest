<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Mapper;

use App\Entity\Article;
use App\Entity\Language;
use App\Entity\ArticleLangData;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ArticleFormDataTransformer implements DataTransformer
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Article $article
     *
     * @return array
     *
     * @throws BadRequestHttpException
     */
    public function transformFromEntity($article): array
    {
        if (!($article instanceof Article)) {
            throw new BadRequestHttpException('First argument must be Article entity');
        }

        $data = [
            'is_active' => $article->getIsActive(),
            'url' => $article->getUrl(),
        ];

        foreach ($article->getArticleLangData() as $articleLangData) {
            $langId = $articleLangData->getLanguage()->getId();
            $data['lang_' . $langId . '_title'] = $articleLangData->getTitle();
            $data['lang_' . $langId . '_text'] = $articleLangData->getText();
            $data['lang_' . $langId . '_meta_keywords'] = $articleLangData->getMetaKeywords();
            $data['lang_' . $langId . '_meta_description'] = $articleLangData->getMetaDescription();
        }

        return $data;
    }

    /**
     * @param array $form
     * @param Article $article
     *
     * @throws BadRequestHttpException
     */
    public function transformToEntity(array $form, $article): void
    {
        if (!($article instanceof Article)) {
            throw new BadRequestHttpException('Second argument must be Article entity');
        }

        /** @var Article $article */
        $article->setIsActive((bool) ($form['is_active'] ?? true))
            ->setUrl((string) ($form['url'] ?? ''));

        $languages = $this->em->getRepository(Language::class)->findBy(['is_active' => true]);

        $articleLangDataRepository = $this->em->getRepository(ArticleLangData::class);

        foreach ($languages as $language) {
            $articleLangData = $articleLangDataRepository->findOneBy([
                'language' => $language,
                'article' => $article,
            ]);

            if ($articleLangData === null) {
                $articleLangData = (new ArticleLangData())
                    ->setArticle($article)
                    ->setLanguage($language);

                $article->addArticleLangData($articleLangData);
            }

            $articleLangData->setTitle((string) ($form['lang_' . $language->getId() . '_title'] ?? ''));
            $articleLangData->setText((string) ($form['lang_' . $language->getId() . '_text'] ?? ''));
            $articleLangData->setMetaKeywords((string) ($form['lang_' . $language->getId() . '_meta_keywords'] ?? ''));
            $articleLangData->setMetaDescription(
                (string) ($form['lang_' . $language->getId() . '_meta_description'] ?? '')
            );
        }
    }
}