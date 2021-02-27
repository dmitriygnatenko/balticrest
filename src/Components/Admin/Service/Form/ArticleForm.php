<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Form;

use App\Components\Admin\Service\Form\Type\CKEditorFormType;
use App\Components\Admin\Service\Form\Type\IsActiveFormType;
use App\Entity\Article;
use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleForm extends AbstractForm
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
        /** @var Article|null $article */
        $article = $options['article'] ?? null;

        $em = $this->entityManager;
        $languages = $em->getRepository(Language::class)->findBy(['is_active' => true]);

        $builder->setRequired(false);

        $builder->add('is_active', IsActiveFormType::class);

        $builder->add('url', TextType::class, [
            'label' => 'URL',
            'constraints' => [
                new Callback([
                    'groups' => self::ALL_VALIDATION_GROUPS,
                    'callback' => static function($value, $context) use ($em, $article) {
                        if ($value === '' || $value === null) {
                            return;
                        }

                        if ($article !== null && $article->getUrl() === $value) {
                            return;
                        }

                        if ($em->getRepository(Article::class)->count(['url' => $value])) {
                            $context->buildViolation('Данный URL уже используется')
                                ->atPath('url')
                                ->addViolation();
                        }
                    }
                ]),
                new NotBlank(['groups' => self::ALL_VALIDATION_GROUPS])
            ]
        ]);

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

            $builder->add('lang_' . $language->getId() . '_meta_keywords', TextType::class, [
                'label' => 'Meta keywords'
            ]);

            $builder->add('lang_' . $language->getId() . '_meta_description', TextType::class, [
                'label' => 'Meta description'
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('article', null)
            ->setRequired('validation_groups');
    }
}