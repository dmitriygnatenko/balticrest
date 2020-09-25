<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\CKEditorFormType;
use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Components\Admin\Service\Form\Type\NewsTagsFormType;
use App\Entity\Language;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsForm extends AbstractType
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

        $languages = $em->getRepository(Language::class)->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('image', TextType::class, [
            'label' => 'Картинка',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])
            ]
        ]);

        $builder->add('publish_date', DateType::class, [
            'label' => 'Дата публикации',
            'constraints' => [
                new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])
            ]
        ]);

        $builder->add('tags', NewsTagsFormType::class);

        /** @var Language $language */
        foreach ($languages as $language) {
            $builder->add('lang_' . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
                'constraints' => in_array($language->getCode(), self::REQUIRED_LANGUAGES, true)
                    ? [new NotBlank(['groups' => [self::VALIDATION_GROUP_CREATE, self::VALIDATION_GROUP_UPDATE]])]
                    : []
            ]);

            $builder->add('lang_' . $language->getId() . '_text', CKEditorFormType::class, [
                'label' => 'Текст',
                'config' => ['height' => 800]
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
            ->setRequired('validation_groups');
    }
}