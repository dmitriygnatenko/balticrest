<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\CityFormType;
use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Components\Admin\Service\Form\Type\PointFormType;
use App\Entity\Language;
use App\Entity\Point;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class PointForm extends AbstractType
{
    /** @var string */
    public const VALIDATION_GROUP_CREATE = 'create';

    /** @var string */
    public const LANG_FIELD_PREFIX = 'lang_';

    /** @var string */
    public const MAIN_LANG = 'ru';

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

        if (!is_a($em, EntityManagerInterface::class)) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $languages = $em->getRepository(Language::class)
            ->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $builder->add('city', CityFormType::class)
            ->add('type', PointFormType::class)
            ->add('is_active', IsActiveFormType::class);

        $builder->add('lat', TextType::class, [
            'label' => 'Широта',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE]])
            ]
        ]);

        $builder->add('lon', TextType::class, [
            'label' => 'Долгота',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE]])
            ]
        ]);

        $builder->add('url', TextType::class, [
            'label' => 'URL',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE]]),
                new Callback([
                    'groups' => [self::VALIDATION_GROUP_CREATE],
                    'callback' => function($value, $context) use ($em) {
                        $count = $em->getRepository(Point::class)->count(['url' => $value]);
                        if ($count > 0) {
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
                new Email(['groups' => [self::VALIDATION_GROUP_CREATE]])
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

        $builder->add('comment', TextAreaType::class, [
            'label' => 'Комментарий',
        ]);

        /** @var Language $language */
        foreach ($languages as $language) {

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
                'constraints' => $language->getCode() === self::MAIN_LANG
                    ? [new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE]])]
                    : []
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_address', TextType::class, [
                'label' => 'Адрес',
                'constraints' => $language->getCode() === self::MAIN_LANG
                    ? [new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE]])]
                    : []
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_desc', TextareaType::class, [
                'label' => 'Описание',
            ]);

            $builder->add(self::LANG_FIELD_PREFIX . $language->getId() . '_short_desc', TextareaType::class, [
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
            ->setRequired('validation_groups');
    }
}
