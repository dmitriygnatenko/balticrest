<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\CityFormType;
use App\Components\Admin\Service\Form\Type\CKEditorFormType;
use App\Components\Admin\Service\Form\Type\DetailedPointFormType;
use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Components\Admin\Service\Form\Type\PointFormType;
use App\Components\Admin\Service\Form\Type\ServicesFormType;
use App\Entity\Language;
use App\Entity\Point;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;

class PointForm extends AbstractForm
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws HttpException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $em = $this->entityManager;

        /** @var Point|null $point */
        $point = $options['point'] ?? null;

        /** @var array $validationGroups */
        $validationGroups = $options['validation_groups'] ?? [];

        $languages = $em->getRepository(Language::class)->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $isTypeDisabled = in_array(self::VALIDATION_GROUP_UPDATE, $validationGroups, true);
        $builder->add('type', PointFormType::class, ['disabled' => $isTypeDisabled]);

        $builder->add('detailed_type', DetailedPointFormType::class);

        $isCityDisabled = in_array(self::VALIDATION_GROUP_UPDATE, $validationGroups, true);
        $builder->add('city', CityFormType::class,  ['disabled' => $isCityDisabled]);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('services', ServicesFormType::class);

        $builder->add('lat', TextType::class, [
            'label' => 'Широта',
            'constraints' => [
                new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS]),
                new Type([
                    'groups' => self::ALL_VALIDATION_GROUPS,
                    'type' => 'numeric'
                ])
            ]
        ]);

        $builder->add('lon', TextType::class, [
            'label' => 'Долгота',
            'constraints' => [
                new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS]),
                new Type([
                    'groups' => self::ALL_VALIDATION_GROUPS,
                    'type' => 'numeric'
                ])
            ]
        ]);

        $builder->add('url', TextType::class, [
            'label' => 'URL',
            'constraints' => [
                new Callback([
                    'groups' => self::ALL_VALIDATION_GROUPS,
                    'callback' => static function($value, $context) use ($em, $point) {
                        if ($value === '' || $value === null) {
                            return;
                        }

                        if ($point !== null && $point->getUrl() === $value) {
                            return;
                        }

                        if ($em->getRepository(Point::class)->count(['url' => $value])) {
                            $context->buildViolation('Данный URL уже используется')
                                ->atPath('url')
                                ->addViolation();
                        }
                    }
                ])
            ]
        ]);

        $builder->add('email', EmailType::class, [
            'label' => 'Электронная почта',
            'constraints' => [
                new Email(['groups' => self::ALL_VALIDATION_GROUPS])
            ]
        ]);

        $builder->add('website', TextType::class, [
            'label' => 'Сайт',
        ]);

        $builder->add('phones', TextType::class, [
            'label' => 'Телефоны',
        ]);

        $builder->add('logo', TextType::class, [
            'label' => 'Логотип',
            'block_prefix' => 'image_selector',
        ]);

        $builder->add('comment', CKEditorFormType::class, [
            'label' => 'Комментарий',
        ]);

        /** @var Language $language */
        foreach ($languages as $language) {
            $builder->add('lang_' . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
                'constraints' => in_array($language->getCode(), self::REQUIRED_LANGUAGES, true)
                    ? [new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS])]
                    : []
            ]);

            $builder->add('lang_' . $language->getId() . '_address', TextType::class, [
                'label' => 'Адрес',
            ]);

            $builder->add('lang_' . $language->getId() . '_desc', CKEditorFormType::class, [
                'label' => 'Описание',
                'config' => ['height' => 500]
            ]);

            $builder->add('lang_' . $language->getId() . '_short_desc', CKEditorFormType::class, [
                'label' => 'Краткое описание',
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('point', null)
            ->setRequired('validation_groups');
    }
}
