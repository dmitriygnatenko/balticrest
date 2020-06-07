<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Mapper;

use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PointFormDataMapper
{
    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public  function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Point $point
     *
     * @return array
     */
    public function mapPointToForm(Point $point): array
    {
        $pointExtData = $point->getData();

        $data = [
            'city' => $point->getCity(),
            'type' => $point->getType(),
            'is_active' => $point->getIsActive(),
            'lat' => $point->getLat(),
            'lon' => $point->getLon(),
            'logo' => $point->getLogo(),
            'url' => $point->getUrl(),
            'email' => $pointExtData[Point::FIELD_EMAIl] ?? '',
            'website' => $pointExtData[Point::FIELD_WEBSITE] ?? '',
            'phones' => $pointExtData[Point::FIELD_PHONES] ?? '',
            'comment' => $pointExtData[Point::FIELD_COMMENT] ?? '',
            'services' => $pointExtData[Point::FIELD_SERVICES] ?? [],
        ];

        foreach ($point->getPointLangData() as $pointLangData) {
            $langId = $pointLangData->getLanguage()->getId();
            $pointLangExtData = $pointLangData->getData();

            $data['lang_' . $langId . '_title'] = $pointLangData->getTitle();
            $data['lang_' . $langId . '_desc'] = $pointLangExtData[PointLangData::FIELD_DESC ?? ''];
            $data['lang_' . $langId . '_short_desc'] = $pointLangExtData[PointLangData::FIELD_SHORT_DESC ?? ''];
            $data['lang_' . $langId . '_address'] = $pointLangExtData[PointLangData::FIELD_ADDRESS ?? ''];
        }

        return $data;
    }

    /**
     * @param array $form
     * @param Point $point
     */
    public function mapFormToPoint(array $form, Point $point): void
    {
        $pointExtData = [
            Point::FIELD_EMAIl => (string) $form['email'] ?? '',
            Point::FIELD_WEBSITE => (string) $form['website'] ?? '',
            Point::FIELD_PHONES => (string) $form['phones'] ?? '',
            Point::FIELD_COMMENT => (string) $form['comment'] ?? '',
            Point::FIELD_SERVICES => (array) $form['services'] ?? [],
        ];

        /** @var Point $point */
        $point->setLat((float) $form['lat'] ?? 0)
            ->setLon((float) $form['lon'] ?? 0)
            ->setLogo((string) $form['logo'] ?? '')
            ->setUrl((string) $form['url'] ?? '')
            ->setIsActive((bool) $form['is_active'] ?? true)
            ->setCity($form['city'] ?? null)
            ->setType($form['type'] ?? null)
            ->setData($pointExtData);

        $languages = $this->em->getRepository(Language::class)
            ->findBy(['is_active' => true]);

        $pointLangDataRepository = $this->em->getRepository(PointLangData::class);

        foreach ($languages as $language) {
            $pointLangData = $pointLangDataRepository->findOneBy([
                'language' => $language,
                'point' => $point,
            ]);

            if ($pointLangData === null) {
                $pointLangData = (new PointLangData())
                    ->setPoint($point)
                    ->setLanguage($language);

                $point->addPointLangData($pointLangData);
            }

            $pointLangData->setTitle((string) $form['lang_' . $language->getId() . '_title'] ?? '');

            $pointLangExtData = [
                PointLangData::FIELD_DESC => (string) $form['lang_' . $language->getId() . '_desc'] ?? '',
                PointLangData::FIELD_SHORT_DESC => (string) $form['lang_' . $language->getId() . '_short_desc'] ?? '',
                PointLangData::FIELD_ADDRESS => (string) $form['lang_' . $language->getId() . '_address'] ?? '',
            ];

            $pointLangData->setData($pointLangExtData);
        }
    }
}