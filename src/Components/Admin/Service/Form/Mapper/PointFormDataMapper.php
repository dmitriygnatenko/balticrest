<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form\Mapper;

use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataMapperInterface;

class PointFormDataMapper implements DataMapperInterface
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
     * @param mixed $data
     * @param iterable $forms
     */
    public function mapDataToForms($data, iterable $forms)
    {
        // TODO: Implement mapDataToForms() method.
    }

    /**
     * @param iterable $form
     * @param mixed $point
     */
    public function mapFormsToData(iterable $form, &$point)
    {
        $pointExtData = [
            Point::FIELD_EMAIl => (string) $form['email'] ?? '',
            Point::FIELD_WEBSITE => (string) $form['website'] ?? '',
            Point::FIELD_PHONES => (string) $form['phones'] ?? '',
            Point::FIELD_COMMENT => (string) $form['comment'] ?? '',
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

        foreach ($languages as $language) {
            $pointLangData = new PointLangData();
            $pointLangData->setPoint($point)
                ->setLanguage($language)
                ->setTitle((string) $form['lang_' . $language->getId() . '_title'] ?? '');

            $pointLangExtData = [
                PointLangData::FIELD_DESC => (string) $form['lang_' . $language->getId() . '_desc'] ?? '',
                PointLangData::FIELD_SHORT_DESC => (string) $form['lang_' . $language->getId() . '_short_desc'] ?? '',
                PointLangData::FIELD_ADDRESS => (string) $form['lang_' . $language->getId() . '_address'] ?? '',
            ];

            $pointLangData->setData($pointLangExtData);
        }
    }
}