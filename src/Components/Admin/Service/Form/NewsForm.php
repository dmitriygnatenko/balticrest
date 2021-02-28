<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\CKEditorFormType;
use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Components\Admin\Service\Form\Type\NewsTagsFormType;
use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewsForm extends AbstractForm
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
        $languages = $this->entityManager->getRepository(Language::class)->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('image', TextType::class, [
            'label' => 'Картинка',
            'constraints' => [
                new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS])
            ]
        ]);

        $builder->add('publish_date', DateType::class, [
            'label' => 'Дата публикации',
            'widget' => 'single_text',
            'constraints' => [
                new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS])
            ]
        ]);

        $builder->add('tags', NewsTagsFormType::class);

        /** @var Language $language */
        foreach ($languages as $language) {
            $builder->add('lang_' . $language->getId() . '_title', TextType::class, [
                'label' => 'Название',
                'constraints' => in_array($language->getCode(), self::REQUIRED_LANGUAGES, true)
                    ? [new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS])]
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
        $resolver->setRequired('validation_groups');
    }
}