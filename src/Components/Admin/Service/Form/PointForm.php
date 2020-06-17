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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;

class PointForm extends AbstractType
{
    /** @var string */
    public const VALIDATION_GROUP_CREATE = 'create';

    /** @var string */
    public const VALIDATION_GROUP_UPDATE = 'update';

    /** @var array */
    public const REQUIRED_LANGUAGES = ['ru', 'en'];

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @throws HttpException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var EntityManager $em */
        $em = $options['em'];

        /** @var Point|null $point */
        $point = $options['point'] ?? null;

        /** @var array $validationGroups */
        $validationGroups = $options['validation_groups'] ?? [];

        $languages = $em->getRepository(Language::class)
            ->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $isTypeDisabled = in_array(self::VALIDATION_GROUP_UPDATE, $validationGroups);
        $builder->add('type', PointFormType::class, ['disabled' => $isTypeDisabled]);

        $builder->add('detailed_type', DetailedPointFormType::class);

        $isCityDisabled = in_array(self::VALIDATION_GROUP_UPDATE, $validationGroups);
        $builder->add('city', CityFormType::class,  ['disabled' => $isCityDisabled]);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('services', ServicesFormType::class);

        $builder->add('lat', TextType::class, [
            'label' => 'Широта',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]]),
                new Type([
                    'groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE],
                    'type' => 'numeric'
                ])
            ]
        ]);

        $builder->add('lon', TextType::class, [
            'label' => 'Долгота',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]]),
                new Type([
                    'groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE],
                    'type' => 'numeric'
                ])
            ]
        ]);

        $builder->add('url', TextType::class, [
            'label' => 'URL',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]]),
                new Callback([
                    'groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE],
                    'callback' => function($value, $context) use ($em, $point) {
                        if ($point !== null && $point->getUrl() === $value) {
                            return;
                        }

                        $count = $em->getRepository(Point::class)->count(['url' => $value]);

                        if ($count) {
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
                new Email(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])
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
        ]);

        $builder->add('comment', CKEditorFormType::class, [
            'label' => 'Комментарий',
        ]);

        /** @var Language $language */
        foreach ($languages as $language) {

            $builder->add('lang_' . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
                'constraints' => in_array($language->getCode(), self::REQUIRED_LANGUAGES)
                    ? [new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])]
                    : []
            ]);

            $builder->add('lang_' . $language->getId() . '_address', TextType::class, [
                'label' => 'Адрес',
                'constraints' => in_array($language->getCode(), self::REQUIRED_LANGUAGES)
                    ? [new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])]
                    : []
            ]);

            $builder->add('lang_' . $language->getId() . '_desc', CKEditorFormType::class, [
                'label' => 'Описание',
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
            ->setRequired('em')
            ->addAllowedTypes('em', EntityManagerInterface::class)
            ->setDefault('point', null)
            ->setRequired('validation_groups');
    }
}
