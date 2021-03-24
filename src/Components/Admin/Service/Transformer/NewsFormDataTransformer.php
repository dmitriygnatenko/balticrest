<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Transformer;

use App\Entity\Language;
use App\Entity\News;
use App\Entity\NewsLangData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NewsFormDataTransformer implements TransformerInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param News $news
     *
     * @return array
     *
     * @throws BadRequestHttpException
     */
    public function transformFromEntity($news): array
    {
        if (!($news instanceof News)) {
            throw new BadRequestHttpException('First argument must be News entity');
        }

        $data = [
            'is_active' => $news->getIsActive(),
            'publish_date' => $news->getPublishDate(),
            'image' => $news->getImage(),
            'tags' => $news->getTags()
        ];

        foreach ($news->getNewsLangData() as $newsLangData) {
            $langId = $newsLangData->getLanguage()->getId();
            $data['lang_' . $langId . '_title'] = $newsLangData->getTitle();
            $data['lang_' . $langId . '_text'] = $newsLangData->getText();
        }

        return $data;
    }

    /**
     * @param array $form
     * @param News $news
     *
     * @throws BadRequestHttpException
     */
    public function transformToEntity(array $form, $news): void
    {
        if (!($news instanceof News)) {
            throw new BadRequestHttpException('Second argument must be News entity');
        }

        $news->setIsActive((bool) ($form['is_active'] ?? true))
            ->setImage((string) ($form['image'] ?? ''))
            ->setTags((array) ($form['tags'] ?? []))
            ->setPublishDate(($form['publish_date'] ?? ''));

        $languages = $this->entityManager->getRepository(Language::class)->findBy(['is_active' => true]);

        $newsLangDataRepository = $this->entityManager->getRepository(NewsLangData::class);

        foreach ($languages as $language) {
            $newsLangData = $newsLangDataRepository->findOneBy([
                'language' => $language,
                'news' => $news,
            ]);

            if ($newsLangData === null) {
                $newsLangData = (new NewsLangData())
                    ->setNews($news)
                    ->setLanguage($language);

                $news->addNewsLangData($newsLangData);
            }

            $newsLangData->setTitle((string) ($form['lang_' . $language->getId() . '_title'] ?? ''));
            $newsLangData->setText((string) ($form['lang_' . $language->getId() . '_text'] ?? ''));
        }
    }
}